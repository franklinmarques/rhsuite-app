<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supervisores_escolas_model extends CI_Model
{

    /**
     * Nome da tabela do banco de dados
     */
    protected $table = 'ei_supervisores_escolas';

    //--------------------------------------------------------------------------

    public function find($where = array())
    {
        if ($where) {
            $this->db->where($where);
        } else {
            $this->db->limit(1);
        }
        return $this->db->get($this->table)->row();
    }

    //--------------------------------------------------------------------------

    public function findAll($where = array())
    {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get($this->table)->result();
    }

    //--------------------------------------------------------------------------

    public function count($where = array())
    {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get($this->table)->num_rows();
    }

    //--------------------------------------------------------------------------

    public function validate($where = array())
    {
        $config = array(
            array('field' => 'id', 'rules' => 'numeric|max_length[11]')
        );

        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            return $this->form_validation->error_string();
        }

        return true;
    }

    //--------------------------------------------------------------------------

    public function revalidate($where = array())
    {
        $config = array(
            array('field' => 'id', 'rules' => 'required|numeric|max_length[11]')
        );

        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            return $this->form_validation->error_string();
        }

        return true;
    }

    //--------------------------------------------------------------------------

    public function insert($data = array())
    {
        return $this->db->insert($this->table, $data) !== false;
    }

    //--------------------------------------------------------------------------

    public function update($data = array(), $where = array())
    {
        return $this->db->update($this->table, $data, $where) !== false;
    }

    //--------------------------------------------------------------------------

    public function delete($where = array())
    {
        if (empty($where)) {
            return false;
        }
        return $this->db->delete($this->table, $where) !== false;
    }
}
