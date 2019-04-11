<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Realizaravaliacao_model extends CI_Model
{

    var $table = 'avaliacao';
    var $column_order = array('nome', null); //set column field database for datatable orderable
    var $column_search = array('nome'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search

                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function getNome($id)
    {
        $this->db->select('nome');
        $this->db->from('usuarios');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $return = $query->result();
        return $return[0]->nome;
    }

    function getCargo($id)
    {
        $query = $this->db->query("SELECT cargos.nome
			FROM `funcionarios_cargos`
			LEFT JOIN cargos ON cargos.id = funcionarios_cargos.`id_cargo`
				WHERE funcionarios_cargos.`id_usuario` = " . $id);
        $return = $query->result();
        return $return[0]->nome;
    }

    function get_datatables($id_avaliacao, $id_usuario)
    {
        $query = $this->db->query("SELECT * FROM avaliacao 
										LEFT JOIN avaliador_avaliados ON avaliador_avaliados.id_avaliacao = avaliacao.id
											WHERE id_avaliacao = " . $id_avaliacao);

        foreach ($query->result() as $k => $dados) {

            switch ($id_usuario) {
                case $dados->avaliador_1:
                    $return['avaliado'][] = $dados->avaliado;
                    break;
                case $dados->avaliador_2:
                    $return['avaliado'][] = $dados->avaliado;
                    break;
                case $dados->avaliador_3:
                    $return['avaliado'][] = $dados->avaliado;
                    break;
                case $dados->avaliador_4:
                    $return['avaliado'][] = $dados->avaliado;
                    break;
                case $dados->avaliador_5:
                    $return['avaliado'][] = $dados->avaliado;
                    break;
            }
            $return['status'] = $dados->status;
        }

        return $return;
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
