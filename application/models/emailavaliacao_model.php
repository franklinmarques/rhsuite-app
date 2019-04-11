<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emailavaliacao_model extends CI_Model
{

    var $table = 'email_avaliacao';
    var $column_order = array('nome', null); //set column field database for datatable orderable
    var $column_search = array('nome'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_idEmpresa($id)
    {
        $this->db->from($this->table);
        $this->db->where('id_usuario_EMPRESA', 57);
        $query = $this->db->get();
        return $query->result();
        ;
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

}
