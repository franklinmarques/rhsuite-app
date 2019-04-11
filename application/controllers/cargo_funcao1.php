<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_funcao extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('cargo_funcao');
    }

    public function ajax_cargo()
    {
        $post = $this->input->post();

        $sql = "SELECT DISTINCT(cargo) AS nome
                FROM usuarios
                WHERE empresa = {$this->session->userdata('empresa')} AND 
                      CHAR_LENGTH(cargo) > 0";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('nome');
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
        foreach ($list as $cargo) {
            $row = array();
            $row[] = $cargo->nome;
            $row[] = '
                      <button class="btn btn-sm btn-info" title="Editar" onclick="delete_cargo(' . "'" . $cargo->nome . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-primary" title="Funções" onclick="delete_cargo(' . "'" . $cargo->nome . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Funções</button>
                      <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_cargo(' . "'" . $cargo->nome . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function ajax_funcao()
    {
        $post = $this->input->post();

        $sql = "SELECT funcao AS nome, cargo
                FROM usuarios
                WHERE empresa = {$this->session->userdata('empresa')} AND 
                      (cargo = '{$post['cargo']}' OR CHAR_LENGTH('{$post['cargo']}') = 0) AND 
                      CHAR_LENGTH(funcao) > 0
                GROUP BY cargo, funcao";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('nome');
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
        foreach ($list as $funcao) {
            $row = array();
            $row[] = $funcao->nome;
            $row[] = $funcao->cargo;
            $row[] = '
                      <button class="btn btn-sm btn-info" title="Editar" onclick="delete_funcao(' . "'" . $funcao->nome . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_funcao(' . "'" . $funcao->nome . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function save_cargo()
    {
        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'cargo' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('cargo' => $nome), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function save_funcao()
    {
        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'funcao' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('funcao' => $nome), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function delete_cargo()
    {
        $id = $this->input->post('id');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'cargo' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('cargo' => null), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function delete_funcao()
    {
        $id = $this->input->post('id');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'funcao' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('funcao' => null), $where);
        }

        echo json_encode(array("status" => true));
    }

}
