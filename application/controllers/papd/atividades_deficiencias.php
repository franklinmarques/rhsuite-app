<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades_deficiencias extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('papd/atividades_deficiencias');
    }

    public function ajax_atividades()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.valor
                FROM (SELECT a.id, 
                             a.nome,
                             FORMAT(a.valor,2,'de_DE') AS valor
                  FROM papd_atividades a
                  WHERE a.id_instituicao= {$this->session->userdata('empresa')}";
        $sql .= ')s';

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome');
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
        foreach ($list as $atividade) {
            $row = array();
            $row[] = $atividade->id;
            $row[] = $atividade->nome;
            $row[] = $atividade->valor;
            $row[] = '
                      <button class="btn btn-xs btn-danger" title="Excluir" onclick="delete_atividade(' . "'" . $atividade->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function ajax_deficiencias()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome
                FROM (SELECT a.id, 
                             a.nome
                  FROM papd_hipotese_diagnostica a
                  WHERE a.id_instituicao= {$this->session->userdata('empresa')}";
        $sql .= ')s';

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome');
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
        foreach ($list as $deficiencia) {
            $row = array();
            $row[] = $deficiencia->id;
            $row[] = $deficiencia->nome;
            $row[] = '
                      <button class="btn btn-xs btn-danger" title="Excluir" onclick="delete_deficiencia(' . "'" . $deficiencia->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function save_deficiencia()
    {
        $id = $this->input->post('id');
        $data = array(
            'nome' => $this->input->post('nome'),
            'id_instituicao' => $this->session->userdata('empresa')
        );
        if ($id) {
            $this->db->update('papd_hipotese_diagnostica', $data, array('id' => $id));
        } else {
            $this->db->insert('papd_hipotese_diagnostica', $data);
        }

        echo json_encode(array("status" => true));
    }

    public function save_atividade()
    {
        $id = $this->input->post('id');
        $data = array(
            'nome' => $this->input->post('nome'),
            'valor' => floatval(str_replace(array('.', ','), array('', '.'), $this->input->post('valor'))),
            'id_instituicao' => $this->session->userdata('empresa')
        );

        if ($id) {
            $this->db->update('papd_atividades', $data, array('id' => $id));
        } else {
            $this->db->insert('papd_atividades', $data);
        }

        echo json_encode(array("status" => true));
    }

    public function delete_deficiencia()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('papd_hipotese_diagnostica', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function delete_atividade()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('papd_atividades', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
