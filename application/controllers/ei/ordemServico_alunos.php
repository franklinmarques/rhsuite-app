<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrdemServico_alunos extends MY_Controller
{
    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar($id_escola = null)
    {
        $this->db->select('a2.nome AS ordemServico, e.id AS id_depto', false);
        $this->db->select('b.nome AS nomeEscola', false);
        $this->db->select('c.nome AS nomeCliente', false);
        $this->db->select('d.contrato AS nomeContrato', false);
        $this->db->select("CONCAT(a2.ano, '/', a2.semestre) AS anoSemestre", false);
        $this->db->join('ei_ordem_servico a2', 'a.id_ordem_servico = a2.id');
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('ei_contratos d', 'd.id_cliente = c.id');
        $this->db->join('empresa_departamentos e', 'e.nome = c.depto', 'left');
        $this->db->where('a.id', $this->uri->rsegment(3, 0));
        $data = $this->db->get('ei_ordem_servico_escolas a')->row();


        $data->alunos = $this->getAlunos();


        $this->load->view('ei/ordemServico_alunos', $data);
    }

    public function montarEstrutura()
    {
        parse_str($this->input->post('busca'), $busca);

        $alunoCursos = $this->getAlunoCursos($busca['id_aluno']);

        $data['aluno_cursos'] = form_dropdown('id_aluno_curso', $alunoCursos, $busca['id_aluno_curso'], 'id="curso" class="form-control filtro"');


        echo json_encode($data);
    }

    public function ajaxList($id_escola = '')
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.curso, 
                       s.aluno,
                       s.data_inicio,
                       s.data_termino,
                       s.modulo
                FROM (SELECT a.id,
                             e.nome AS curso,
                             c.nome AS aluno,
                             DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio,
                             DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino,
                             a.modulo
                      FROM ei_ordem_servico_alunos a
                      INNER JOIN ei_ordem_servico_escolas b ON 
                                 b.id = a.id_ordem_servico_escola
                      INNER JOIN ei_alunos c ON 
                                 c.id = a.id_aluno
                      INNER JOIN ei_alunos_cursos d ON 
                                 d.id = a.id_aluno_curso
                      INNER JOIN ei_cursos e ON 
                                 e.id = d.id_curso
                      WHERE b.id = {$id_escola}) s";
        $records = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();


        $data = array();
        foreach ($list as $ei) {
            $row = array();
            $row[] = $ei->curso;
            $row[] = $ei->aluno;
            $row[] = $ei->data_inicio;
            $row[] = $ei->data_termino;
            $row[] = $ei->modulo;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_aluno(' . $ei->id . ')" title="Editar aluno(a)"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_aluno(' . $ei->id . ')" title="Excluir aluno(a)"><i class="glyphicon glyphicon-trash"></i> </button>
                     ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function ajaxEdit()
    {
        $id = $this->input->post('id');
        parse_str($this->input->post('busca'), $busca);

        $this->db->where('id', $id);
        $data = $this->db->get('ei_ordem_servico_alunos')->row();
        $data->data_inicio = date('d/m/Y', strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date('d/m/Y', strtotime(str_replace('-', '/', $data->data_termino)));

        $alunoCursos = $this->getAlunoCursos($data->id_aluno);

        $data->aluno_curso = form_dropdown('id_aluno_curso', $alunoCursos, $data->id_aluno_curso, 'id="aluno_curso" class="form-control filtro"');

        echo json_encode($data);
    }

    public function ajaxAdd()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        $erro = '';
        if (empty($data['id_aluno'])) {
            $erro .= "O aluno não pode ficar em branco. \n";
        }
        if (empty($data['id_aluno_curso'])) {
            $erro .= "O curso não pode ficar em branco. \n";
        }
        if (empty($data['data_inicio'])) {
            $erro .= "A data de início não pode ficar em branco. \n";
        }
        if (empty($data['data_termino'])) {
            $erro .= "A data de término não pode ficar em branco. \n";
        }
        if (empty($data['modulo'])) {
            $erro .= "O módulo não pode ficar em branco.";
        }
        if ($erro) {
            exit(json_encode(array('erro' => $erro)));
        }
        $this->db->select('data_inicio, data_termino, modulo');
        $this->db->where('id_ordem_servico_escola', $data['id_ordem_servico_escola']);
        $this->db->where('id_aluno', $data['id_aluno']);
        $this->db->where('id_aluno_curso', $data['id_aluno_curso']);
        $this->db->where("((data_inicio = '{$data['data_inicio']}' OR data_termino = '{$data['data_termino']}') OR modulo = '{$data['modulo']}')", null, false);
        $count = $this->db->get('ei_ordem_servico_alunos')->num_rows();
        if ($count) {
            exit(json_encode(array('erro' => 'Os dias ou módulo já foram cadastrados para este cuidador.')));
        }
        $data['data_inicio'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_termino'])));

        $status = $this->db->insert('ei_ordem_servico_alunos', $data);

        echo json_encode(array('status' => $status !== false));
    }

    public function ajaxUpdate()
    {
        $id = $this->input->post('id');
        $data = $this->input->post();
        unset($data['id']);
        $erro = '';
        if (empty($data['id_aluno'])) {
            $erro .= "O aluno não pode ficar em branco. \n";
        }
        if (empty($data['id_aluno_curso'])) {
            $erro .= "O curso não pode ficar em branco. \n";
        }
        if (empty($data['data_inicio'])) {
            $erro .= "A data de início não pode ficar em branco. \n";
        }
        if (empty($data['data_termino'])) {
            $erro .= "A data de término não pode ficar em branco. \n";
        }
        if (empty($data['modulo'])) {
            $erro .= "O módulo não pode ficar em branco.";
        }
        if ($erro) {
            exit(json_encode(array('erro' => $erro)));
        }
        $this->db->select('data_inicio, data_termino, modulo');
        $this->db->where('id !=', $id);
        $this->db->where('id_ordem_servico_escola', $data['id_ordem_servico_escola']);
        $this->db->where('id_aluno', $data['id_aluno']);
        $this->db->where('id_aluno_curso', $data['id_aluno_curso']);
        $this->db->where("((data_inicio = '{$data['data_inicio']}' OR data_termino = '{$data['data_termino']}') OR modulo = '{$data['modulo']}')", null, false);
        $count = $this->db->get('ei_ordem_servico_alunos')->num_rows();
        if ($count) {
            exit(json_encode(array('erro' => 'Os dias ou módulo já foram cadastrados para este cuidador.')));
        }
        $data['data_inicio'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_termino'])));


        $status = $this->db->update('ei_ordem_servico_alunos', $data, array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_ordem_servico_alunos', array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }

    /*
    |---------------------------------------------------------------------------
    | Funções privadas
    |---------------------------------------------------------------------------
    */

    private function getAlunos()
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('ei_alunos_cursos b', 'b.id_aluno = a.id');
        $this->db->join('ei_cursos c', 'c.id = b.id_curso');
        $this->db->join('ei_escolas_cursos d', 'd.id_curso = c.id');
        $this->db->join('ei_escolas e', 'e.id = d.id_escola AND b.id_escola = e.id');
        $this->db->join('ei_diretorias f', 'f.id = e.id_diretoria');
        $this->db->join('ei_ordem_servico_escolas g', 'g.id_escola = e.id');
        $this->db->where('f.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('g.id', $this->uri->rsegment(3));
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('ei_alunos a')->result();


        return ['' => 'selecione...'] + array_column($rows, 'nome', 'id');
    }

    private function getAlunoCursos($id = '')
    {
        $this->db->select('a.id, b.nome');
        $this->db->join('ei_cursos b', 'b.id = a.id_curso');
        $this->db->where('a.id_aluno', $id);
        $this->db->order_by('b.nome', 'asc');
        $rows = $this->db->get('ei_alunos_cursos a')->result();


        return array('' => 'selecione...') + array_column($rows, 'nome', 'id');
    }

}