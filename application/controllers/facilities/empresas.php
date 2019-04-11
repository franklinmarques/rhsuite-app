<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresas extends MY_Controller
{

    public function index()
    {
        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('facilities_empresas')->result();


        $data['idFacilityEmpresa'] = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');


        $this->load->view('facilities/empresas', $data);
    }


    public function ajaxList()
    {
        $this->db->select('a.id, c.id AS id_revisao, b.nome AS empresa, a.nome AS item, c.nome');
        $this->db->select("(CASE a.ativo WHEN 1 THEN 'Ativo' WHEN 0 THEN 'Facility' END) AS categoria");
        $this->db->select("(CASE c.tipo WHEN 'V' THEN 'Vistoria' WHEN 'M' THEN 'Manutenção' END) AS tipo");
        $this->db->join('facilities_empresas b', 'b.id = a.id_facility_empresa');
        $this->db->join('facilities_empresas_revisoes c', 'c.id_item = a.id', 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $query = $this->db->get('facilities_empresas_itens a');

        $config = array(
            'search' => ['empresa', 'categoria', 'nome'],
            'order' => ['empresa', 'item', 'categoria', 'tipo', 'nome']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->empresa,
                $row->item,
                $row->categoria,
                '<button class="btn btn-sm btn-info" onclick="edit_item(' . $row->id . ')" title="Editar ativo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_item(' . $row->id . ')" title="Excluir ativo"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="add_revisao(' . $row->id . ')" title="Gerenciar vistorias do item"><i class="glyphicon glyphicon-plus"></i> Vistoria/manutenção</button>',
                $row->tipo,
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_revisao(' . $row->id_revisao . ')" title="Editar ativo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_revisao(' . $row->id_revisao . ')" title="Excluir ativo"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }


    public function ajaxEdit()
    {
        $id = $this->input->post('id');

        $data = $this->db->get_where('facilities_empresas_itens', ['id' => $id])->row();

        echo json_encode($data);
    }


    public function ajaxEditRevisao()
    {
        $id = $this->input->post('id');

        $data = $this->db->get_where('facilities_empresas_revisoes', ['id' => $id])->row();

        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $data = $this->input->post();
        if (strlen($data['id_facility_empresa']) == 0) {
            exit(json_encode(['erro' => 'A empresa é obrigatória']));
        }
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        if (strlen($data['ativo']) == 0) {
            exit(json_encode(['erro' => 'O tipo do item é obrigatório']));
        }

        $this->db->where('id_facility_empresa', $data['id_facility_empresa']);
        $this->db->where('nome', $data['nome']);
        $this->db->where('ativo', $data['ativo']);
        $numRows = $this->db->get('facilities_empresas_itens')->num_rows();
        if ($numRows) {
            exit(json_encode(['erro' => 'O item a ser cadastrado já existe']));
        }

        $status = $this->db->insert('facilities_empresas_itens', $data);

        echo json_encode(['status' => $status !== false]);
    }


    public function ajaxAddRevisao()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        if (strlen($data['tipo']) == 0) {
            exit(json_encode(['erro' => 'O tipo do item é obrigatório']));
        }

        $this->db->where('id_item', $data['id_item']);
        $this->db->where('nome', $data['nome']);
        $this->db->where('tipo', $data['tipo']);
        $numRows = $this->db->get('facilities_empresas_revisoes')->num_rows();
        if ($numRows) {
            exit(json_encode(['erro' => 'O item a ser cadastrado já existe']));
        }

        $status = $this->db->insert('facilities_empresas_revisoes', $data);

        echo json_encode(['status' => $status !== false]);
    }


    public function ajaxUpdate()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        if (strlen($data['id_facility_empresa']) == 0) {
            exit(json_encode(['erro' => 'A empresa é obrigatória']));
        }
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        if (strlen($data['ativo']) == 0) {
            exit(json_encode(['erro' => 'O tipo do item é obrigatório']));
        }

        $this->db->where('id_facility_empresa', $data['id_facility_empresa']);
        $this->db->where('nome', $data['nome']);
        $this->db->where('ativo', $data['ativo']);
        $this->db->where('id !=', $id);
        $numRows = $this->db->get('facilities_empresas_itens')->num_rows();
        if ($numRows) {
            exit(json_encode(['erro' => 'Já existe outro item cadstrado com as mesmas características']));
        }

        $status = $this->db->update('facilities_empresas_itens', $data, ['id' => $id]);

        echo json_encode(['status' => $status !== false]);
    }


    public function ajaxUpdateRevisao()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        if (strlen($data['tipo']) == 0) {
            exit(json_encode(['erro' => 'O tipo do item é obrigatório']));
        }

        $this->db->where('id_item', $data['id_item']);
        $this->db->where('nome', $data['nome']);
        $this->db->where('tipo', $data['tipo']);
        $this->db->where('id !=', $id);
        $numRows = $this->db->get('facilities_empresas_revisoes')->num_rows();
        if ($numRows) {
            exit(json_encode(['erro' => 'Já existe outro item cadstrado com as mesmas características']));
        }

        $status = $this->db->update('facilities_empresas_revisoes', $data, ['id' => $id]);

        echo json_encode(['status' => $status !== false]);
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_empresas_itens', ['id' => $id]);

        echo json_encode(['status' => $status !== false]);
    }


    public function ajaxDeleteRevisao()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_empresas_revisoes', ['id' => $id]);

        echo json_encode(['status' => $status !== false]);
    }


}
