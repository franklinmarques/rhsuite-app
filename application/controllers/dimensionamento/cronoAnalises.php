<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CronoAnalises extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $data = ['empresa' => $this->session->userdata('empresa')];
        $this->load->view('dimensionamento/crono_analises', $data);
    }

    //==========================================================================
    public function ajaxList()
    {
        $this->db->select('nome, data_inicio, data_termino, id');
        $this->db->select(["DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio_de"], false);
        $this->db->select(["DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino_de"], false);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        if ($this->input->post('ativos')) {
            $this->db->where('(NOW() BETWEEN data_inicio AND data_termino)');
        }
        $query = $this->db->get('dimensionamento_crono_analises');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();
        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->data_inicio_de,
                $row->data_termino_de,
                '<button class="btn btn-sm btn-info" onclick="edit_crono_analise(' . $row->id . ')" title="Editar crono análise"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_crono_analise(' . $row->id . ')" title="Excluir crono análise"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" onclick="edit_executores(' . $row->id . ')" title="Gerenciar executores"><i class="glyphicon glyphicon-list"></i> Executores</button>
                 <button class="btn btn-sm btn-primary" onclick="apontamentos(' . $row->id . ')" title="Gerenciar apontamentos"><i class="glyphicon glyphicon-list"></i> Apontamentos</button>
                 <button class="btn btn-sm btn-primary" onclick="relatorio(' . $row->id . ')" title="Pelatório de performance"><i class="glyphicon glyphicon-list"></i> Rel. Performance</button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $this->db->select('id, id_empresa, nome');
        $this->db->select(["DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio"], false);
        $this->db->select(["DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino"], false);
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('dimensionamento_crono_analises')->row();
        if (empty($data)) {
            exit(json_encode(['erro' => 'Análise não encontrada ou excluída recentemente.']));
        }
        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $this->validarCronoAnalise();
        $this->db->trans_start();
        $this->db->insert('dimensionamento_crono_analises', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar a análise.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $this->validarCronoAnalise();
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_crono_analises', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar a análise.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    private function validarCronoAnalise()
    {
        $data = $this->input->post();
        unset($data['id'], $data['id_empresa']);
        if (empty(array_filter($data))) {
            exit(json_encode(['erro' => 'O formulário está vazio.']));
        }
        if ($data['data_inicio']) {
            $_POST['data_inicio'] = preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $data['data_inicio']);
        }
        if ($data['data_termino']) {
            $_POST['data_termino'] = preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $data['data_termino']);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nome', '"Nome"', 'required|max_length[255]');
        $this->form_validation->set_rules('data_inicio', '"Data Início"', 'required|valid_date');
        $this->form_validation->set_rules('data_termino', '"Data Término"', 'required|valid_date|after_or_equal_date[data_inicio]');

        if ($this->form_validation->run() == false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }

        $this->db->where('id !=', $this->input->post('id'));
        $this->db->where('id_empresa', $this->input->post('id_empresa'));
        $this->db->where('nome', $this->input->post('nome'));
        $count = $this->db->get('dimensionamento_crono_analises')->num_rows();

        if ($count > 0) {
            exit(json_encode(['erro' => 'O campo "Nome" já existe, ele deve ser único.']));
        }
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_crono_analises', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a análise.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function editarExecutores()
    {
        $this->db->where('id', $this->input->post('id'));
        $count = $this->db->get('dimensionamento_crono_analises')->num_rows();
        if ($count == 0) {
            exit(json_encode(['erro' => 'Análise não encontrada ou excluída recentemente.']));
        }

        $this->db->select('id, nome');
        $this->db->where('tipo', 'funcionario');
        $this->db->where('status', 1);
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();

        $this->db->select('id_usuario');
        $this->db->where('id_crono_analise', $this->input->post('id'));
        $executores = $this->db->get('dimensionamento_executores')->result();

        $data['executores'] = form_multiselect(
            '',
            array_column($usuarios, 'nome', 'id'),
            array_column($executores, 'id_usuario')
        );

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarExecutores()
    {
        $idCronoAnalise = $this->input->post('id_crono_analise');
        $executores = $this->input->post('id_usuario');

        $this->db->trans_start();

        $this->db->where('id_crono_analise', $idCronoAnalise);
        $this->db->where_not_in('id_usuario', $executores);
        $this->db->delete('dimensionamento_executores');

        $this->db->select("'{$idCronoAnalise}' AS id_crono_analise", false);
        $this->db->select('a.id AS id_usuario');
        $this->db->join('dimensionamento_executores b', "b.id_usuario = a.id AND b.id_crono_analise = '{$idCronoAnalise}'", 'left');
        $this->db->where_in('a.id', $executores);
        $this->db->where('b.id', null);
        $this->db->order_by('a.nome', 'asc');
        $data = $this->db->get('usuarios a')->result_array();

        if ($data) {
            $this->db->insert_batch('dimensionamento_executores', $data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao salvar os executores da análise.']));
        }

        echo json_encode(['status' => true]);
    }


}
