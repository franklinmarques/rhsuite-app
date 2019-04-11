<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_model extends CI_Model
{

    var $table = 'competencias';
    var $column_id = 'id'; //set column field database for datatable orderable
    var $column_order = array('nome', null); //set column field database for datatable orderable
    var $column_search = array('nome'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function carregaComboCargos($id)
    {
        $this->db->order_by("nome", "asc");
        $this->db->where("id", $id);
        $query = $this->db->get("cargos");
        return $query;
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;

        $search = $this->input->post('search');
        $order = $this->input->post('order');
        foreach ($this->column_search as $item) { // loop column 
            if ($search['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $search['value']);
                } else {
                    $this->db->or_like($item, $search['value']);
                }

                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end();
                } //close bracket
                $i++;
            }
        }

        if ($order) { // here order processing
//            $this->db->order_by($this->column_order[$order['0']['column']], $order['0']['dir']);
            $this->db->order_by('2', $order['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($id, $tipo_competencia)
    {
        $this->_get_datatables_query();
        if ((isset($_POST['length']) and isset($_POST['start'])) and $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $this->db->where(array('id_cargo' => $id, 'tipo_competencia' => $tipo_competencia));
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
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
