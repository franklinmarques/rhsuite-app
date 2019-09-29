<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal_estagios extends MY_Controller
{

//    public function __construct()
//    {
//        parent::__construct();
//
//        $this->load->model('requisicoes_pessoal_estagios_model', 'estagios');
//    }

    public function index()
    {
        $data = ['empresa' => $this->session->userdata('empresa')];

        $this->load->view('requisicaoPessoal_estagios', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $query = $this->db
            ->select('nome, destino_email, email_responsavel, mensagem, id')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->get('requisicoes_pessoal_estagios');

        $this->load->library('dataTables');
        $rows = $this->datatables->generate($query);

        $data = array();
        foreach ($rows->data as $row) {
            $data[] = array(
                $row->nome,
                $row->destino_email,
                $row->email_responsavel,
                $row->mensagem,
                '<button class="btn btn-sm btn-info" onclick="edit_estagio(' . $row->id . ');" title="Editar texto e-mail de apoio"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_estagio(' . $row->id . ');" title="Excluir texto e-mail de apoio"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $rows->data = $data;

        echo json_encode($rows);
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        $data = $this->db
            ->where('id', $this->input->post('id'))
            ->get('requisicoes_pessoal_estagios')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Texto não encontrado ou excluído recentemente.']));
        }

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->input->post();
        $status = $this->db->insert('requisicoes_pessoal_estagios', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxUpdate()
    {
        $id = $this->input->post('id');

        $data = $this->input->post();
        $this->db->set($data);
        $this->db->where('id', $id);
        $status = $this->db->update('requisicoes_pessoal_estagios');

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('requisicoes_pessoal_estagios', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
