<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento_detalhes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {$sql = "SELECT s.id, 
                       s.codigo,
                       s.nome
                FROM (SELECT a.id, 
                             a.codigo,
                             a.nome
                      FROM alocacao_eventos a
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}) s";

        $recordsTotal = $this->db->query($sql)->num_rows();
//        print_r($this->db->conn_id);exit;
        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('apontamento_detalhes', $data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.codigo,
                       s.nome
                FROM (SELECT a.id, 
                             a.codigo,
                             a.nome
                      FROM alocacao_eventos a
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.codigo', 's.nome', 's.tipo');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " 
                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
                }
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $apontamento) {
            $row = array();
            $row[] = $apontamento->codigo;
            $row[] = $apontamento->nome;
            $row[] = '
                      <button class="btn btn-sm btn-primary" onclick="edit_evento(' . $apontamento->id . ')" title="Editar evento"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_evento(' . $apontamento->id . ')" title="Excluir evento"><i class="glyphicon glyphicon-trash"></i></button>
                     ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('alocacao_eventos', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }
        unset($data['id']);
        $status = $this->db->insert('alocacao_eventos', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }
        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('alocacao_eventos', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_eventos', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
