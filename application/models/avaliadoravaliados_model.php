<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliadoravaliados_model extends CI_Model
{

    var $table = 'avaliador_avaliados';
    var $column_order = array('nome', null); //set column field database for datatable orderable
    var $column_search = array('nome'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function getNome($id)
    {
        $resUsuarios = $this->db->query("SELECT nome FROM usuarios WHERE id = " . $id);
        $res = $resUsuarios->result();
        return $res[0]->nome;
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

    public function delete_by_id($id_avaliacao, $id_avaliado)
    {
        $this->db->query("delete from avaliador_avaliados where id = {$id_avaliacao} AND {$id_avaliado} ");
    }

}
