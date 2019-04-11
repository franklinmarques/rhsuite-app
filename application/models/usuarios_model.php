<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model
{

    private $table = 'usuarios';
    private $salt = '@#d13g0tr1nd4d3!';

    public function setPassword($value)
    {
        return md5($this->salt . $value);
    }

    public function getUsuario($email, $senha)
    {
        $row = $this->db->get_where('usuarios', array('email' => $email, 'senha' => $senha));
        if ($row->num_rows() == 1) {
            return $row->row();
        } else {
            $this->db->select("a.*, b.url, b.cabecalho, 'candidato' AS tipo, NULL AS hash_acesso", false);
            $this->db->join('usuarios b', 'b.id = a.empresa');
            $row = $this->db->get_where('recrutamento_usuarios a', array('a.email' => $email, 'a.senha' => $senha));
            if ($row->num_rows() == 1) {
                return $row->row();
            } else {
                $this->db->select("a.*, b.id AS empresa, b.url, b.cabecalho, 'cliente' AS tipo, NULL AS nivel_acesso, NULL AS hash_acesso", false);
                $this->db->join('usuarios b', 'b.id = a.id_empresa');
                $row = $this->db->get_where('cursos_clientes a', array('a.email' => $email, 'a.senha' => $senha));
                if ($row->num_rows() == 1) {
                    return $row->row();
                } else {
                    return false;
                }
            }
        }
    }

    public function getCargos()
    {
        $this->db->select('DISTINCT(cargo)');
        if ($this->session->userdata('tipo') != 'administrador') {
            $this->db->where('empresa', $this->session->userdata('empresa'));
        }
        $this->db->where('CHAR_LENGTH(cargo) > 0');
        $rows = $this->db->get($this->table)->result();

        $data = array('' => 'selecione...');
        foreach ($rows as $row) {
            $data[$row->cargo] = $row->cargo;
        }
        return $data;
    }

    public function getFuncoes($cargo = null)
    {
        $this->db->select('DISTINCT(funcao), cargo');
        if ($this->session->userdata('tipo') != 'administrador') {
            $this->db->where('empresa', $this->session->userdata('empresa'));
        }
        if ($cargo) {
            $this->db->where('cargo', $cargo);
        }
        $this->db->where('CHAR_LENGTH(funcao) > 0');
        $rows = $this->db->get($this->table)->result();

        $data = array('' => 'selecione...');
        foreach ($rows as $row) {
            $data[$row->funcao] = $row->funcao;
        }
        return $data;
    }

    public function isValid()
    {
        $this->load->form_validation();

        $config = array(
            array('field' => 'id', 'label' => 'ID', 'rules' => 'integer|max_length[11]'),
            array('field' => 'empresa', 'label' => 'Empresa', 'rules' => 'integer|max_length[11]'),
            array('field' => 'tipo', 'label' => 'Tipo', 'rules' => 'required|max_length[20]'),
            array('field' => 'url', 'label' => 'URL', 'rules' => 'required|valid_url|max_length[255]'),
            array('field' => 'nome', 'label' => 'Nome', 'rules' => 'required|max_length[255]'),
            array('field' => 'funcao', 'label' => 'Função', 'rules' => 'max_length[100]'),
            array('field' => 'foto', 'label' => 'Foto', 'rules' => 'required|max_length[255]'),
            array('field' => 'cabecalho', 'label' => 'Cabeçalho', 'rules' => ''),
            array('field' => 'imagem_inicial', 'label' => 'Imagem Inicial', 'rules' => 'required'),
            array('field' => 'assinatura_digital', 'label' => 'Assinatura Digital', 'rules' => ''),
            array('field' => 'email', 'label' => 'E-mail', 'rules' => 'required|valid_email|max_length[255]'),
            array('field' => 'senha', 'label' => 'Senha', 'rules' => 'required|max_length[32]'),
            array('field' => 'token', 'label' => 'Token', 'rules' => 'required|max_length[255]'),
            array('field' => 'datacadastro', 'label' => 'Data Cadastro', 'rules' => 'required|is_date'),
            array('field' => 'dataeditado', 'label' => 'Data Editado', 'rules' => 'required|is_date'),
            array('field' => 'status', 'label' => 'Status', 'rules' => 'required|integer|max_length[2]'),
            array('field' => 'nivel_acesso', 'label' => 'nivel_acesso', 'rules' => 'required|integer|max_length[11]')
        );

        $this->form_validation->set_rules($config);

        return $this->form_validation->run();
    }

}
