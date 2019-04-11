<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal_fontes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('requisicaoPessoal_fontes', $data);
    }

    public function ajaxList()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome
                FROM (SELECT a.id, 
                             a.nome
                      FROM requisicoes_pessoal_fontes a
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}) s";

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
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $apontamento) {
            $row = array();
            $row[] = $apontamento->nome;
            $row[] = '
                      <button class="btn btn-sm btn-info" onclick="edit_fonte(' . $apontamento->id . ')" title="Editar evento"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_fonte(' . $apontamento->id . ')" title="Excluir evento"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function ajaxListAprovador()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id_usuario, 
                       s.nome
                FROM (SELECT a.id_usuario, 
                             b.nome
                      FROM requisicoes_pessoal_aprovadores a
                      INNER JOIN usuarios b ON b.id = a.id_usuario
                      WHERE b.empresa = {$this->session->userdata('empresa')}) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.nome');
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
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $aprovador) {
            $row = array();
            $row[] = $aprovador->nome;
            $row[] = '
                      <button class="btn btn-sm btn-danger" onclick="delete_aprovador(' . $aprovador->id_usuario . ')" title="Excluir aprovador"><i class="glyphicon glyphicon-trash"></i></button>
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

    public function ajaxEdit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('requisicoes_pessoal_fontes', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajaxEditAprovador()
    {
        $this->db->select('DISTINCT(a.cargo) AS cargo', false);
        $this->db->join('requisicoes_pessoal_aprovadores b', 'b.id_usuario = a.id', 'left');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.tipo', 'funcionario');
        $this->db->where('CHAR_LENGTH(a.cargo) >', 0);
        $this->db->where('b.id_usuario', null);
        $this->db->order_by('a.cargo', 'asc');
        $cargos = $this->db->get('usuarios a')->result();
        $options['cargo'] = ['' => 'Todos'] + array_column($cargos, 'cargo', 'cargo');


        $this->db->select('a.id, a.nome');
        $this->db->join('requisicoes_pessoal_aprovadores b', 'b.id_usuario = a.id', 'left');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.tipo', 'funcionario');
        $this->db->where('b.id_usuario', null);
        $this->db->order_by('a.nome', 'asc');
        $usuarios = $this->db->get('usuarios a')->result();
        $options['id_usuario'] = ['' => 'selecione...'] + array_column($usuarios, 'nome', 'id');


        $data['cargo'] = form_dropdown('cargo', $options['cargo'], '');
        $data['id_usuario'] = form_dropdown('id_usuario', $options['id_usuario'], '');

        echo json_encode($data);
    }


    public function atualizarAprovador()
    {
        $cargo = $this->input->post('cargo');
        $idUsuario = $this->input->post('id_usuario');

        $this->db->select('a.id, a.nome');
        $this->db->join('requisicoes_pessoal_aprovadores b', 'b.id_usuario = a.id', 'left');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.tipo', 'funcionario');
        $this->db->where('b.id_usuario', null);
        if ($cargo) {
            $this->db->where('a.cargo', $cargo);
        }
        $this->db->order_by('a.nome', 'asc');
        $usuarios = $this->db->get('usuarios a')->result();
        $options['id_usuario'] = ['' => 'selecione...'] + array_column($usuarios, 'nome', 'id');

        $data['id_usuario'] = form_dropdown('id_usuario', $options['id_usuario'], $idUsuario);


        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $data = $this->input->post();
        if (empty($data['nome'])) {
            exit(json_encode(array('error' => 'O nome é obrigatório')));
        }
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }

        $this->db->where('id_empresa', $data['id_empresa']);
        $this->db->where('nome', $data['nome']);
        if ($this->db->get('requisicoes_pessoal_fontes')->num_rows() > 0) {
            exit(json_encode(array('error' => 'Já existe uma fonte cadastrada com este nome')));
        }

        unset($data['id']);
        $status = $this->db->insert('requisicoes_pessoal_fontes', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxAddAprovador()
    {
        $data = $this->input->post();
        if (empty($data['id_usuario'])) {
            exit(json_encode(array('error' => 'O nome é obrigatório')));
        }

        $this->db->where('id_usuario', $data['id_usuario']);
        if ($this->db->get('requisicoes_pessoal_aprovadores')->num_rows() > 0) {
            exit(json_encode(array('error' => 'O usuário já está cadastrado como aprovador')));
        }

        $status = $this->db->insert('requisicoes_pessoal_aprovadores', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdate()
    {
        $data = $this->input->post();
        if (empty($data['nome'])) {
            exit(json_encode(array('error' => 'O nome é obrigatório')));
        }
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }

        $this->db->where('id_empresa', $data['id_empresa']);
        $this->db->where('nome', $data['nome']);
        $this->db->where('id !=', $data['id']);
        if ($this->db->get('requisicoes_pessoal_fontes')->num_rows() > 0) {
            exit(json_encode(array('error' => 'Já existe uma fonte cadastrada com este nome')));
        }

        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('requisicoes_pessoal_fontes', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('requisicoes_pessoal_fontes', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDeleteAprovador()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('requisicoes_pessoal_aprovadores', array('id_usuario' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
