<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_clientes_model extends CI_Model
{

    private static $table = 'cursos_clientes';


    public function validate()
    {
        $_POST['foto'] = utf8_encode($_FILES['foto']['name'] ?? '');

        $config = array(
            array(
                'field' => 'id',
                'label' => 'ID',
                'rules' => 'integer|max_length[11]'
            ),
            array(
                'field' => 'id_empresa',
                'label' => 'ID empresa',
                'rules' => 'required|integer|max_length[11]'
            ),
            array(
                'field' => 'nome',
                'label' => 'Usuário',
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'cliente',
                'label' => 'Cliente',
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'email',
                'label' => 'E-mail',
                'rules' => 'required|valid_email|max_length[255]'
            ),
            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => 'required|max_length[32]'
            ),
            array(
                'field' => 'confirmar_senha',
                'label' => 'Confirmar senha',
                'rules' => 'required|max_length[32]|matches[senha]'
            ),
            array(
                'field' => 'foto',
                'label' => 'Foto',
                'rules' => 'uploaded[foto]|mime_in[foto.gif,jpg,png]|max_length[255]'
            ),
            array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'regex_match[/^(0|1)$/]'
            )
        );

        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_array();
        }

        return true;
    }


    public function revalidate()
    {
        $_POST['foto'] = utf8_encode($_FILES['foto']['name'] ?? '');
        $post = $this->input->post();

        $config = array(
            array(
                'field' => 'id',
                'label' => 'ID',
                'rules' => 'required|integer|max_length[11]'
            ),
            array(
                'field' => 'id_empresa',
                'label' => 'ID empresa',
                'rules' => 'required|integer|max_length[11]'
            ),
            array(
                'field' => 'nome',
                'label' => 'Usuário',
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'cliente',
                'label' => 'Cliente',
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'email',
                'label' => 'E-mail',
                'rules' => 'required|valid_email|max_length[255]'
            ),
            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => ($post['senha'] or $post['confirmar_senha'] ? 'required|' : '') . 'max_length[32]'
            ),
            array(
                'field' => 'confirmar_senha',
                'label' => 'Confirmar senha',
                'rules' => ($post['senha'] or $post['confirmar_senha'] ? 'required|' : '') . 'max_length[32]|matches[senha]'
            ),
            array(
                'field' => 'foto',
                'label' => 'Foto',
                'rules' => 'uploaded[foto]|mime_in[foto.gif,jpg,png]|max_length[255]'
            ),
            array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'regex_match[/^(0|1)$/]'
            )
        );

        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_array();
        }

        return true;
    }


    public function insert($data)
    {
        $this->db->trans_begin();
        $this->db->insert(self::$table, $data);
        $uploaded = $this->uploadFile();
        if ($this->db->trans_status() === false and $uploaded !== true) {
            $this->db->trans_rollback();
            return $this->db->display_error() . $uploaded;
        }

        $this->db->trans_commit();

        return true;
    }


    public function insertID($data)
    {
        $this->db->trans_begin();
        $this->db->insert(self::$table, $data);
        $uploaded = $this->uploadFile();
        if ($this->db->trans_status() === false and $uploaded !== true) {
            $this->db->trans_rollback();
            return $this->db->display_error() . $uploaded;
        }

        $this->db->trans_commit();

        return $this->db->insert_id();
    }


    public function update($data, $where)
    {
        $this->db->trans_begin();
        $this->db->update(self::$table, $data, $where);
        $uploaded = $this->uploadFile();
        if ($this->db->trans_status() === false and $uploaded !== true) {
            $this->db->trans_rollback();
            return $this->db->display_error() . $uploaded;
        }

        $this->eraseFile($where);
        $this->db->trans_commit();

        return true;
    }


    public function delete($where)
    {
        $this->db->trans_begin();
        $this->db->delete(self::$table, $where);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return $this->db->display_error();
        }

        $this->eraseFile($where);
        $this->db->trans_commit();

        return $this->db->trans_status();
    }


    public function uploadFile()
    {
        if (!empty($_FILES['foto'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['foto']['name']);

            if ($this->load->is_loaded('upload')) {
                $this->upload->initialize($config);
            } else {
                $this->load->library('upload', $config);
            }

            if ($this->upload->do_upload('foto') === false) {
                return $this->upload->display_errors();
            }
        }

        return true;
    }


    public function eraseFile($where)
    {
        $rows = $this->db->get_where(self::$table, $where)->result();

        foreach ($rows as $row) {
            @unlink('./imagens/usuarios/' . $row->foto);
        }
    }

}
