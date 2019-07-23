<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Detalhes extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('apontamento_detalhes', $data);
    }

    //==========================================================================
    public function ajaxList()
    {
        $query = $this->db
            ->select('codigo, nome, id')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->get('alocacao_eventos');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->codigo,
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_evento(' . $row->id . ');" title="Editar evento"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_evento(' . $row->id . ');" title="Excluir evento"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $data = $this->db
            ->where('id', $this->input->post('id'))
            ->get('alocacao_eventos')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Detalhe de evento não encontrado ou excluído recentemente.']));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $this->validarDados();

        $data = $this->input->post();
        unset($data['id']);

        $this->db->trans_start();
        $this->db->insert('alocacao_eventos', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível cadastrar o detalhe de evento.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $this->validarDados();

        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('alocacao_eventos', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível alterar o detalhe de evento.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    private function validarDados()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('codigo', '"Código evento"', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('nome', '"Nome evento"', 'trim|required|max_length[255]');

        if ($this->form_validatio->run() == false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $this->db->trans_start();
        $this->db->delete('alocacao_eventos', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível excluir o detalhe de evento.']));
        }

        echo json_encode(['status' => true]);
    }

}
