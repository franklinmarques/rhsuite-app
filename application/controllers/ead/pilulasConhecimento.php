<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PilulasConhecimento extends MY_Controller
{

    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $areas = $this->db->order_by('nome', 'asc')->get('cursos_pilulas_areas')->result();

        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $cursos = $this->db->get('cursos')->result();

        $deptos = $this->db->order_by('nome', 'asc')->get('empresa_departamentos')->result();

        $this->db->where('empresa', $empresa);
        $this->db->where('tipo', 'funcionario');
        $this->db->where('status', 1);
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();

        $data = array(
            'empresa' => $empresa,
            'areas' => ['' => 'selecione...'] + array_column($areas, 'nome', 'id'),
            'cursos' => ['' => 'selecione...'] + array_column($cursos, 'nome', 'id'),
            'deptos' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
            'usuarios' => array_column($usuarios, 'nome', 'id')
        );

        $this->load->view('ead/pilulas_conhecimento', $data);
    }


    public function ajaxList()
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('c.nome AS area_conhecimento, b.nome AS curso');
        $this->db->select("(CASE a.publico WHEN 1 THEN 'Aberto' ELSE 'Fechado' END) AS tipo", false);
        $this->db->select("GROUP_CONCAT(e.nome ORDER BY e.nome SEPARATOR '<br>') AS usuarios, a.id", false);
        $this->db->join('cursos b', 'b.id = a.id_curso');
        $this->db->join('cursos_pilulas_areas c', 'c.id = a.id_area_conhecimento', 'left');
        $this->db->join('cursos_pilulas_colaboradores d', 'd.id_pilula = a.id', 'left');
        $this->db->join('usuarios e', 'e.id = d.id_usuario', 'left');
        if ($empresa) {
            $this->db->where('a.id_empresa', $empresa);
        }
        $this->db->group_by('a.id');
        $query = $this->db->get('cursos_pilulas a');


        $config = array(
            'search' => ['area_conhecimento', 'curso', 'usuarios']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);


        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->area_conhecimento,
                $row->curso,
                $row->tipo,
                $row->usuarios,
                '<button class="btn btn-sm btn-info" onclick="edit_pilula(' . $row->id . ');" title="Editar MIF"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_pilula(' . $row->id . ');" title="Excluir MIF"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }


    public function ajaxEdit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('cursos_pilulas', ['id' => $id])->row();

        $this->db->select('id_usuario');
        $this->db->where('id_pilula', $id);
        $usuarios = $this->db->get('cursos_pilulas_colaboradores')->result();

        $data->usuarios = array_column($usuarios, 'id_usuario');

        echo json_encode($data);
    }


    public function montarEstrutura()
    {
        $idDepto = $this->input->post('id_depto');
        $idArea = $this->input->post('id_area');
        $idSetor = $this->input->post('id_setor');
        if (empty($idDepto)) {
            $idArea = '';
        }
        if (empty($idArea)) {
            $idSetor = '';
        }
        $usuariosSelecionados = $this->input->post('usuarios_selecionados');
        if (empty($usuariosSelecionados)) {
            $usuariosSelecionados = [0];
        }
        $strUusuariosSelecionados = implode(',', $usuariosSelecionados);

        $this->db->where('id_departamento', $idDepto);
        $this->db->order_by('nome', 'asc');
        $areas = $this->db->get('empresa_areas')->result();
        $areas = ['' => 'Todas'] + array_column($areas, 'nome', 'id');


        $this->db->where('id_area', $idArea);
        $this->db->order_by('nome', 'asc');
        $setores = $this->db->get('empresa_setores')->result();
        $setores = ['' => 'Todos'] + array_column($setores, 'nome', 'id');


        $sql = "SELECT a.id, a.nome FROM usuarios a
                LEFT JOIN empresa_departamentos b ON b.id = a.id_depto
                LEFT JOIN empresa_areas c ON c.id = a.id_area
                LEFT JOIN empresa_setores d ON d.id = a.id_setor
                WHERE a.empresa = '{$this->session->userdata('empresa')}' AND 
                      a.tipo = 'funcionario' AND 
                      a.status = 1 AND
                      a.id NOT IN ({$strUusuariosSelecionados}) AND
                      ((a.id_depto = '{$idDepto}' OR CHAR_LENGTH('{$idDepto}') = 0) AND
                       (a.id_area = '{$idArea}' OR CHAR_LENGTH('{$idArea}') = 0) AND
                       (a.id_setor = '{$idSetor}' OR CHAR_LENGTH('{$idSetor}') = 0))
                UNION 
                SELECT e.id, e.nome FROM usuarios e
                WHERE e.empresa = '{$this->session->userdata('empresa')}' AND 
                      e.tipo = 'funcionario' AND 
                      e.status = 1 AND 
                      e.id IN ({$strUusuariosSelecionados})
                ORDER BY nome ASC";
        $usuarios = array_column($this->db->query($sql)->result(), 'nome', 'id');


        $data = array(
            'area' => form_dropdown('', $areas, $idArea),
            'setor' => form_dropdown('', $setores, $idSetor),
            'usuarios' => form_multiselect('', $usuarios, $usuariosSelecionados)
        );


        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $data = $this->input->post();
        $usuarios = $data['id_usuario'] ?? [];
        unset($data['id_usuario']);

        $this->db->trans_start();

        $this->db->insert('cursos_pilulas', $data);

        if (count($usuarios) > 0) {
            $idPilula = $this->db->insert_id();
            $data2 = array();

            foreach ($usuarios as $usuario) {
                $data2[] = array(
                    'id_pilula' => $idPilula,
                    'id_usuario' => $usuario
                );
            }

            $this->db->insert_batch('cursos_pilulas_colaboradores', $data2);
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxUpdate()
    {
        $data = $this->input->post();
        $usuarios = $data['id_usuario'] ?? [];
        $idPilula = $data['id'];
        unset($data['id'], $data['id_usuario']);

        $this->db->trans_start();

        $this->db->update('cursos_pilulas', $data, ['id' => $idPilula]);

        $this->db->where('id_pilula', $idPilula);
        $this->db->where_not_in('id_usuario', $usuarios + [0]);
        $this->db->delete('cursos_pilulas_colaboradores');


        $this->db->where('id_pilula', $idPilula);
        $usuariosCadastrados = $this->db->get('cursos_pilulas_colaboradores')->result();
        $usuariosCadastrados = array_column($usuariosCadastrados, 'id_usuario');

        $usuarios = array_diff($usuarios, $usuariosCadastrados);

        if (count($usuarios) > 0) {
            $data2 = array();

            foreach ($usuarios as $usuario) {
                $data2[] = array(
                    'id_pilula' => $idPilula,
                    'id_usuario' => $usuario
                );
            }

            $this->db->insert_batch('cursos_pilulas_colaboradores', $data2);
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');

        $this->db->trans_start();
        $this->db->delete('cursos_pilulas', array('id' => $id));
        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

}
