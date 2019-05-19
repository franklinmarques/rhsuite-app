<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades_scheduler extends MY_Controller
{

    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('atividades_scheduler', $data);
    }


    public function ajaxList()
    {
        $this->db->select('atividade, objetivos, data_limite, envolvidos, observacoes, processo_roteiro, id');
        $this->db->select(["DATE_FORMAT(data_limite, '%d/%m/%Y') AS data_limite_de"], false);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $query = $this->db->get('atividades_scheduler');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->atividade,
                $row->objetivos,
                $row->data_limite_de,
                $row->envolvidos,
                $row->observacoes,
                $row->processo_roteiro,
                '<button class="btn btn-sm btn-info" onclick="edit_atividade(' . $row->id . ');" title="Editar"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_atividade(' . $row->id . ');" title="Excluir"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info disabled" onclick="imprimir_atividade(' . $row->id . ');" title="Imprimir"><i class="glyphicon glyphicon-print"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function ajaxEdit()
    {
        $this->db->select('id, id_empresa, atividade, objetivos envolvidos, observacoes');
        $this->db->select(["DATE_FORMAT(data_limite, '%d/%m/%Y') AS data_limite"], false);
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('atividades_scheduler')->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Erro ao editar a atividade.']));
        }

        echo json_encode($data);
    }


    public function ajaxAdd()
    {

    }


    public function ajaxUpdate()
    {

    }


    public function ajaxDelete()
    {

    }


    public function pdf()
    {

    }


}
