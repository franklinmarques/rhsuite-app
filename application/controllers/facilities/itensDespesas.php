<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ItensDespesas extends MY_Controller
{

    public function index()
    {
        $data['idSala'] = null;
        $data['empresa'] = $this->session->userdata('empresa');

        $this->db->select('DISTINCT(tipo)', false);
        $this->db->where('ativo', 1);
        $this->db->where('tipo IS NOT NULL');
        $rows = $this->db->get('facilities_itens')->result();
        $data['tipos'] = ['' => 'digite ou selecione...'] + array_column($rows, 'tipo', 'tipo');

        $this->load->view('facilities/itens_despesas', $data);
    }


    public function ajaxList()
    {
        $this->db->select('a.nome AS empresa, a.id');
        $this->db->select('b.nome AS unidade, b.id AS id_unidade');
        $this->db->select('c.nome AS item, c.id AS id_item');
        $this->db->join('facilities_contas_unidades b', 'b.id_conta_empresa = a.id', 'left');
        $this->db->join('facilities_contas_itens c', 'c.id_unidade = b.id', 'left');
        $query = $this->db->get('facilities_contas_empresas a');


        $options = array(
            'search' => ['empresa', 'unidade', 'item']
        );

        $this->load->library('dataTables', $options);


        $output = $this->datatables->generate($query);


        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->empresa,
                '<button class="btn btn-sm btn-info" onclick="edit_empresa(' . $row->id . ')" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_empresa(' . $row->id . ')" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="add_unidade(' . $row->id . ')" title="Gerenciar unidade"><i class="glyphicon glyphicon-plus"></i> Unidades</button>',
                $row->unidade,
                '<button class="btn btn-sm btn-info" onclick="edit_unidade(' . $row->id_unidade . ')" title="Editar unidade"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_unidade(' . $row->id_unidade . ')" title="Excluir unidade"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="add_item(' . $row->id_unidade . ')" title="Gerenciar itens"><i class="glyphicon glyphicon-plus"></i> Itens</button>',
                $row->item,
                '<button class="btn btn-sm btn-info" onclick="edit_item(' . $row->id_item . ')" title="Editar item"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_item(' . $row->id_item . ')" title="Excluir item"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }


        $output->data = $data;


        echo json_encode($output);
    }


    public function ajaxEdit()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $data = $this->db->get('facilities_contas_empresas')->row();

        echo json_encode($data);
    }


    public function ajaxEditUnidade()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $data = $this->db->get('facilities_contas_unidades')->row();

        echo json_encode($data);
    }


    public function ajaxEditItem()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $data = $this->db->get('facilities_contas_itens')->row();

        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome da empresa é obrigatório']));
        }
        $status = $this->db->insert('facilities_contas_empresas', $data);

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxAddUnidade()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome da unidade é obrigatório']));
        }
        $status = $this->db->insert('facilities_contas_unidades', $data);

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxAddItem()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        $status = $this->db->insert('facilities_contas_itens', $data);

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxUpdate()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome da empresa é obrigatório']));
        }

        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('facilities_contas_empresas', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxUpdateUnidade()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome da unidade é obrigatório']));
        }

        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('facilities_contas_unidades', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxUpdateItem()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }

        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('facilities_contas_itens', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_contas_empresas', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxDeleteUnidade()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_contas_unidades', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxDeleteItem()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_contas_itens', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
