<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estruturas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('estruturas');
    }

    public function ajax_list()
    {
        $post = $this->input->post();

        $sql = "SELECT depto, area, setor
                FROM usuarios
                WHERE empresa = {$this->session->userdata('empresa')} AND 
                      CHAR_LENGTH(depto) > 0";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('depto, area, setor');
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
        foreach ($list as $depto) {
            $row = array();
            $row[] = $depto->depto;
            $row[] = $depto->area;
            $row[] = $depto->setor;
            $row[] = '
                      <button class="btn btn-sm btn-info" title="Editar" onclick="edit_depto(' . "'" . $depto->depto . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_depto(' . "'" . $depto->depto . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function ajax_depto()
    {
        $post = $this->input->post();

        $sql = "SELECT DISTINCT(depto) AS nome
                FROM usuarios
                WHERE empresa = {$this->session->userdata('empresa')} AND 
                      CHAR_LENGTH(depto) > 0";

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
        foreach ($list as $depto) {
            $row = array();
            $row[] = $depto->nome;
            $row[] = '
                      <button class="btn btn-xs btn-danger" title="Excluir" onclick="delete_depto(' . "'" . $depto->nome . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function ajax_area()
    {
        $post = $this->input->post();

        $sql = "SELECT DISTINCT(area) AS nome
                FROM usuarios
                WHERE empresa = {$this->session->userdata('empresa')} AND 
                      depto = '{$post['depto']}' AND 
                      CHAR_LENGTH(area) > 0";

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
        foreach ($list as $area) {
            $row = array();
            $row[] = $area->nome;
            $row[] = '
                      <button class="btn btn-xs btn-danger" title="Excluir" onclick="delete_area(' . "'" . $area->nome . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function ajax_setor()
    {
        $post = $this->input->post();

        $sql = "SELECT DISTINCT(setor) AS nome
                FROM usuarios
                WHERE empresa = {$this->session->userdata('empresa')} AND 
                      depto = '{$post['depto']}' AND 
                      area = '{$post['area']}' AND 
                      CHAR_LENGTH(setor) > 0";

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
        foreach ($list as $setor) {
            $row = array();
            $row[] = $setor->nome;
            $row[] = '
                      <button class="btn btn-xs btn-danger" title="Excluir" onclick="delete_setor(' . "'" . $setor->nome . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function save_depto()
    {
        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'depto' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('depto' => $nome), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function save_area()
    {
        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'area' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('area' => $nome), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function save_setor()
    {
        $id = $this->input->post('id');
        $nome = $this->input->post('nome');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'setor' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('setor' => $nome), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function delete_depto()
    {
        $id = $this->input->post('id');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'depto' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('depto' => null), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function delete_area()
    {
        $id = $this->input->post('id');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'area' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('area' => null), $where);
        }

        echo json_encode(array("status" => true));
    }

    public function delete_setor()
    {
        $id = $this->input->post('id');
        $where = array(
            'empresa' => $this->session->userdata('empresa'),
            'setor' => $id
        );
        if ($id) {
            $this->db->update('usuarios', array('setor' => null), $where);
        }

        echo json_encode(array("status" => true));
    }

}
