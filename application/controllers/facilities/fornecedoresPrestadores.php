<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FornecedoresPrestadores extends MY_Controller
{

    public function index()
    {
        $data = array(
            'idEmpresa' => $this->session->userdata('empresa'),
            'tipos' => $this->getTipos()
        );

        $this->load->view('facilities/fornecedores_prestadores', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $post = $this->input->post();

        $this->db->select('nome, tipo, vinculo, pessoa_contato, telefone, email, id');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        if (strlen($post['tipo'])) {
            $this->db->where('status', $post['tipo']);
        }
        if (strlen($post['status'])) {
            $this->db->where('status', $post['status']);
        }
        $query = $this->db->get('facilities_fornecedores_prestadores');

        $this->load->library('dataTables');

        $rows = $this->datatables->generate($query);


        $data = array();

        $tipos = $this->getTipos();
        $tipos[''] = 'Todos';

        foreach ($rows->data as $row) {
            $data[] = array(
                $row->nome,
                $tipos[$row->tipo],
                $row->vinculo,
                $row->pessoa_contato,
                $row->telefone,
                $row->email,
                '<button class="btn btn-sm btn-info" onclick="edit_os(' . $row->id . ');" title="Editar fornecedor(a)/prestador(a)"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_os(' . $row->id . ');" title="Excluir fornecedor(a)/prestador(a)"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $status = ['' => 'Todos', '1' => 'Ativos', '0' => 'Inativos'];

        $rows->status = form_dropdown('busca_status', $status, $post['status'], 'class="form-control input-sm" aria-controls="table" onchange="reload_table();"');
        $rows->tipo = form_dropdown('busca_tipo', $tipos, $post['tipo'], 'class="form-control input-sm" aria-controls="table" style="width: 250px;"  onchange="reload_table();"');
        $rows->data = $data;

        echo json_encode($rows);
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        $data = $this->getData($this->input->post('id'));

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('facilities_fornecedores_prestadores', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxUpdate()
    {
        $id = $this->input->post('id');

        $data = $this->setData();
        $this->db->set($data);
        $this->db->where('id', $id);
        $status = $this->db->update('facilities_fornecedores_prestadores');

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_fornecedores_prestadores', ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    /* -------------------------------------------------------------------------
     *
     * -------------------------------------------------------------------------
     */

    protected function getData($id = '')
    {
        $this->db->where('id', $id);
        $fornecedoresPrestadores = $this->db->get_where('facilities_fornecedores_prestadores')->row();

        if (empty($fornecedoresPrestadores)) {
            $keys = $this->db->list_fields('facilities_fornecedores_prestadores');
            $values = array_pad([], count($keys), null);

            return array_combine($keys, $values);
        }

        return $fornecedoresPrestadores;
    }

    // -------------------------------------------------------------------------

    protected function setData()
    {
        $data = $this->input->post();

        if (!empty($data['id_empresa']) === false) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }

        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do fornecedor/prestador é obrigatório.']));
        }

        if ($data['tipo'] === '') {
            exit(json_encode(['erro' => 'O tipo do fornecedor/prestador é obrigatório.']));
        }

        if (strlen($data['vinculo']) == 0) {
            $data['vinculo'] = null;
        }

        if (strlen($data['pessoa_contato']) == 0) {
            $data['pessoa_contato'] = null;
        }

        if (strlen($data['telefone']) == 0) {
            $data['telefone'] = null;
        }

        if (strlen($data['email']) == 0) {
            $data['email'] = null;
        }

        if (!isset($data['status'])) {
            $data['status'] = 0;
        }

        unset($data['id']);

        return $data;
    }

    // -------------------------------------------------------------------------

    public function getTipos()
    {
        return array(
            '' => 'selecione...',
            '1' => 'Fornecedor de insumos',
            '2' => 'Fornecedor de equipamentos',
            '3' => 'Prestador de serviços elétricos',
            '4' => 'Prestador de serviços hidráulicos',
            '5' => 'Prestador de serviços de alvenaria/pintura/civil',
            '0' => 'Outros'
        );
    }

}
