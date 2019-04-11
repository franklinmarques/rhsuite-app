<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionarios_model extends CI_Model
{

    var $table = 'funcionarios_cargos';
    var $column_order = array('nome', null); //set column field database for datatable orderable
    var $column_search = array('nome'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function __OLD_getFuncionariosByEmpresa($id_empresa)
    {
        $resUsuarios = $this->db->query("SELECT 
													funcionarios_cargos.id as id_funcionarios_cargos,
													usuarios.id, 
													nome, 
													id_cargo 
														FROM usuarios 
															LEFT JOIN funcionarios_cargos on funcionarios_cargos.id_usuario = usuarios.id
																WHERE empresa = " . $id_empresa);

        return $resUsuarios->result();
    }

    function getFuncionariosByEmpresa($id_empresa)
    {

        //selecte de usuarioas
        $resUsuarios = $this->db->query("SELECT 
													id, 
													nome 
														FROM usuarios 
																WHERE empresa = " . $id_empresa);


        // percorrendo usuarios
        foreach ($resUsuarios->result() as $k => $val) {

            //select usuarios vinculados
            $resUsuariosCargos = $this->db->query("SELECT 
													nome, 
													id_usuario,
													id_cargo
														FROM funcionarios_cargos
															LEFT JOIN usuarios ON funcionarios_cargos.id_usuario = usuarios.id
																WHERE id_usuario = " . $val->id);

            //array de todos os usuarios
            $arrayUsuarios[$val->id] = $val;

            //percorrendo usuarios vinculados
            foreach ($resUsuariosCargos->result() as $kCargos => $valCargos) {
                //array de usuarios selecionados
                //print_r($valCargos);
                $arrayUsuarios[$valCargos->id_usuario] = $valCargos;
            }
        }


        return $arrayUsuarios;
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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
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

        $this->db->query("delete from funcionarios_cargos where id_cargo = " . $id);
    }

}
