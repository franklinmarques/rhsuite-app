<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Equipes extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $departamentos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();


        $data = [
            'depto' => ['' => 'Todos'] + array_column($departamentos, 'nome', 'id'),
            'area' => ['' => 'Todas'],
            'setor' => ['' => 'Todos']
        ];


        $this->load->view('dimensionamento/equipes', $data);
    }

    //==========================================================================
    public function gerenciar()
    {
        $departamento = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->where('id', $this->uri->rsegment(3))
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->row();


        $data = [
            'depto' => [$departamento->id => $departamento->nome],
            'area' => ['' => 'Todas'],
            'setor' => ['' => 'Todos']
        ];


        $this->load->view('dimensionamento/equipes', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');


        $rowAreas = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_departamentos b', 'b.id = a.id_departamento')
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->where('b.id', $idDepto)
            ->order_by('a.nome', 'asc')
            ->get('empresa_areas a')
            ->result();


        $areas = array_column($rowAreas, 'nome', 'id');


        $rowSetores = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->join('empresa_departamentos c', 'c.id = b.id_departamento')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('c.id', $idDepto)
            ->where('b.id', $idArea)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();


        $setores = array_column($rowSetores, 'nome', 'id');


        $data['area'] = form_dropdown('', ['' => 'Todas'] + $areas, $idArea);
        $data['setor'] = form_dropdown('', ['' => 'Todos'] + $setores, $idSetor);


        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxList()
    {
        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');
//        $idProcesso = $this->input->post('id_processo');
//        $idAtividade = $this->input->post('id_atividade');
//        $idEtapa = $this->input->post('id_etapa');


        $this->db
            ->select('a.nome, c.nome AS usuario, a.id')
            ->join('dimensionamento_equipes_membros b', 'b.id_equipe = a.id')
            ->join('usuarios c', 'c.id = b.id_usuario')
            ->join('empresa_departamentos d', 'd.id = c.id_depto OR d.nome = c.depto')
            ->join('empresa_areas e', 'e.id = c.id_area OR e.nome = c.area')
            ->join('empresa_setores f', 'f.id = c.id_setor OR f.nome = c.setor')
            ->where('d.id_empresa', $this->session->userdata('empresa'));
        if ($idDepto) {
            $this->db->where('d.id', $idDepto);
        }
        if ($idArea) {
            $this->db->where('e.id', $idArea);
        }
        if ($idSetor) {
            $this->db->where('f.id', $idSetor);
        }
        $query = $this->db->group_by('b.id')->get('dimensionamento_equipes a');


        $config = ['search' => ['nome', 'usuario']];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);


        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_equipe(' . $row->id . ')" title="Gerenciar equipe"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_equipe(' . $row->id . ')" title="Excluir equipe"><i class="glyphicon glyphicon-trash"></i></button>',
                $row->usuario
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxNew()
    {
        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');

        $this->db
            ->select('a.id, a.nome')
            ->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
            ->join('empresa_areas c', 'c.id = a.id_area OR c.nome = a.area')
            ->join('empresa_setores d', 'd.id = a.id_setor OR d.nome = a.setor')
            ->where('a.empresa', $this->session->userdata('empresa'))
            ->where('a.tipo', 'funcionario')
            ->where('a.status', 1)
            ->where('b.id', $idDepto);
        if ($idArea) {
            $this->db->where('c.id', $idArea);
        }
        if ($idSetor) {
            $this->db->where('d.id', $idSetor);
        }
        $usuarios = $this->db
            ->order_by('a.nome', 'asc')
            ->get('usuarios a')
            ->result();

        $data = [
            'id_depto' => $idDepto,
            'id_area' => $idArea,
            'id_setor' => $idSetor,
            'membros' => form_multiselect('', array_column($usuarios, 'nome', 'id'), [])
        ];


        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $equipe = $this->db
            ->select('id, nome, id_depto, id_area, id_setor, total_componentes')
            ->where('id', $this->input->post('id'))
            ->get('dimensionamento_equipes')
            ->row();

        if (empty($equipe)) {
            exit(json_encode(['erro' => 'Equipe não encontrada ou excluída recentemente.']));
        }

        $usuarios = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
            ->join('empresa_areas c', 'c.id = a.id_area OR c.nome = a.area')
            ->join('empresa_setores d', 'd.id = a.id_setor OR d.nome = a.setor')
            ->where('a.empresa', $this->session->userdata('empresa'))
            ->where('a.tipo', 'funcionario')
            ->where('a.status', 1)
            ->where('b.id', $equipe->id_depto);
        if ($equipe->id_area) {
            $this->db->where('c.id', $equipe->id_area);
        }
        if ($equipe->id_setor) {
            $this->db->where('d.id', $equipe->id_setor);
        }
        $usuarios = $this->db
            ->order_by('a.nome', 'asc')
            ->get('usuarios a')
            ->result();


        $membros = $this->db
            ->select('b.id')
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->where('id_equipe', $equipe->id)
            ->order_by('b.nome', 'asc')
            ->get('dimensionamento_equipes_membros a')
            ->result();

        $data = [
            'id' => $equipe->id,
            'nome' => $equipe->nome,
            'total_componentes' => $equipe->total_componentes,
            'membros' => form_multiselect(
                '',
                array_column($usuarios, 'nome', 'id'),
                array_column($membros, 'id')
            )
        ];


        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $this->validarDados();

        $idArea = $this->input->post('id_area');
        $idSetor = $this->input->post('id_setor');
        $membros = $this->input->post('id_usuario');

        $data = [
            'id_empresa' => $this->session->userdata('empresa'),
            'id_depto' => $this->input->post('id_depto'),
            'id_area' => strlen($idArea) > 0 ? $idArea : null,
            'id_setor' => strlen($idSetor) > 0 ? $idSetor : null,
            'nome' => $this->input->post('nome'),
            'total_componentes' => count($membros)
        ];

        $this->db->trans_start();

        $this->db->insert('dimensionamento_equipes', $data);

        $idEquipe = $this->db->insert_id();

        foreach ($membros as $membro) {
            $data2 = [
                'id_equipe' => $idEquipe,
                'id_usuario' => $membro
            ];

            $this->db->insert('dimensionamento_equipes_membros', $data2);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar a equipe.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $this->validarDados();

        $idEquipe = $this->input->post('id');
        $novosMembros = $this->input->post('id_usuario');

        $data = [
            'nome' => $this->input->post('nome'),
            'total_componentes' => count($novosMembros)
        ];

        $this->db->trans_start();

        $this->db->update('dimensionamento_equipes', $data, ['id' => $idEquipe]);

        $this->db
            ->where('id_equipe', $idEquipe)
            ->where_not_in('id_usuario', $novosMembros)
            ->delete('dimensionamento_equipes_membros');

        $membros = $this->db
            ->select('id_usuario')
            ->where('id_equipe', $idEquipe)
            ->get('dimensionamento_equipes_membros')
            ->result();

        $novosMembros = array_diff($novosMembros, array_column($membros, 'id_usuario'));

        foreach ($novosMembros as $novoMembro) {
            $data2 = [
                'id_equipe' => $idEquipe,
                'id_usuario' => $novoMembro
            ];

            $this->db->insert('dimensionamento_equipes_membros', $data2);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao salvar a equipe.']));
        }
        echo json_encode(['status' => true]);
    }

    //==========================================================================
    private function validarDados()
    {
        $data = array_filter([
            $this->input->post('nome'),
            $this->input->post('total_componentes'),
            $this->input->post('id_usuario')
        ]);

        if (empty($data)) {
            exit(json_encode(['erro' => 'O formulário está vazio.']));
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nome', '"Nome"', 'required|max_length[255]');
        $this->form_validation->set_rules('total_componentes', '"Qtde componentes"', 'is_natural|max_length[11]');
        $this->form_validation->set_rules('id_usuario', '"Colaboradores selecionados"', 'required');

        if ($this->form_validation->run() === false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_equipes', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a equipe.']));
        }

        echo json_encode(['status' => true]);
    }


}
