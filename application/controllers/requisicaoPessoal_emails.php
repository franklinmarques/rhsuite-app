<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal_emails extends MY_Controller
{

    public function index()
    {
        $data = ['empresa' => $this->session->userdata('empresa')];

        $this->load->view('requisicaoPessoal_emails', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $this->db->select('email, colaborador');
        $this->db->select("(CASE tipo_usuario WHEN 1 THEN 'Selecionador' WHEN 2 THEN 'Departamento de Pessoal' WHEN 3 THEN 'Gestão de Pessoas' WHEN 4 THEN 'Administrador' END) AS tipo_usuario", false);
        $this->db->select("(CASE tipo_email WHEN 1 THEN 'Nova Requisição de Pessoal' WHEN 2 THEN 'Solicitação de agendamento Exame Médico' WHEN 3 THEN 'Nova requisição + Solicitação de agendamento' WHEN 4 THEN 'Administrador' END) AS tipo_email, id", false);
        $query = $this->db->get('requisicoes_pessoal_emails');

        $this->load->library('dataTables');
        $rows = $this->datatables->generate($query);

        $data = array();
        foreach ($rows->data as $row) {
            $data[] = array(
                $row->email,
                $row->colaborador,
                $row->tipo_usuario,
                $row->tipo_email,
                '<button class="btn btn-sm btn-info" onclick="edit_email(' . $row->id . ');" title="Editar e-mail de apoio"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_email(' . $row->id . ');" title="Excluir e-mail de apoio"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $rows->data = $data;

        echo json_encode($rows);
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        echo json_encode($this->getData($this->input->post('id')));
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('requisicoes_pessoal_emails', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxUpdate()
    {
        $id = $this->input->post('id');

        $data = $this->setData();
        $this->db->set($data);
        $this->db->where('id', $id);
        $status = $this->db->update('requisicoes_pessoal_emails');

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('requisicoes_pessoal_emails', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    /* -------------------------------------------------------------------------
     *
     * -------------------------------------------------------------------------
     */

    private function getData($id = '')
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        return $this->db->get_where('requisicoes_pessoal_emails')->row();
    }

    // -------------------------------------------------------------------------

    private function setData()
    {
        $data = $this->input->post();

        $data2 = $data;
        unset($data2['id'], $data2['id_empresa']);
        if (empty(array_filter($data2))) {
            exit(json_encode(['erro' => 'O formulário está vazio.']));
        }
        unset($data2);

        if (strlen($data['email']) == 0) {
            exit(json_encode(['erro' => 'O campo <strong>E-mail</strong> é obrigatório.']));
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(['erro' => 'O campo <strong>E-mail</strong> deve conter um endereço de e-mail válido.']));
        }

        if (empty($data['colaborador'])) {
            exit(json_encode(['erro' => 'O campo <strong>Colaborador(a)</strong> é obrigatório.']));
        }

        if (!empty($data['id_empresa']) == false) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }

        if (empty($data['tipo_usuario'])) {
            exit(json_encode(['erro' => 'O campo <strong>Tipo colaborador(a)</strong> é obrigatório.']));
        }

        if (empty($data['tipo_email'])) {
            $data['tipo_email'] = null;
        }

        unset($data['id']);

        return $data;
    }

}
