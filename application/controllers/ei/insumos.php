<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Insumos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->userdata('nivel'), array(0, 4, 7, 8, 9, 10))) {
            redirect(site_url('home'));
        }
    }

    //==========================================================================
    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('ei/insumos', $data);
    }

    //==========================================================================
    public function gerenciar($id_aluno = null)
    {
        /*$this->db->select('id, nome');
        if ($id_aluno) {
            $this->db->where('id', $id_aluno);
            $data['alunos'] = array();
        } else {
            $data['alunos'] = array('' => 'Todos');
        }
        $rows = $this->db->get('ei_alunos')->result();

        if (empty($rows)) {
            exit;
        }

        foreach ($rows as $row) {
            $data['alunos'][$row->id] = $row->nome;
        }*/

        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('ei/insumos', $data);
    }

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.nome,
                       s.tipo
                FROM (SELECT a.id,
                             a.nome,
                             a.tipo
                      FROM ei_insumos a 
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.tipo');
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
        foreach ($list as $insumo) {
            $row = array();
            $row[] = $insumo->nome;
            $row[] = $insumo->tipo;
            $row[] = '
                      <button class="btn btn-sm btn-info" onclick="edit_insumo(' . $insumo->id . ')" title="Editar insumo"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_insumo(' . $insumo->id . ')" title="Excluir insumo"><i class="glyphicon glyphicon-trash"></i></button>
                     ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        echo json_encode($output);
    }

    //==========================================================================
    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('ei_insumos', array('id' => $id))->row();

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_add()
    {
        $data = $this->input->post();
        unset($data['id']);
        $status = $this->db->insert('ei_insumos', $data);

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_update()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('ei_insumos', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_insumos', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
