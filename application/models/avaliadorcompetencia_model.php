<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliadorcompetencia_model extends CI_Model
{

    var $table = 'competencias';
    var $column_order = array('nome', null); //set column field database for datatable orderable
    var $column_search = array('nome'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function carregaComboCompetencia($id, $tipo_competencia)
    {
        $query = $this->db->query("SELECT 
										cargos.id as id_cargo, 
										cargos.nome as nome_cargo, 
										competencias.id as id_competencia, 
										competencias.nome as nome_competencia 
											FROM cargos 
												LEFT JOIN competencias ON cargos.id = competencias.id_cargo 
													WHERE competencias.id = {$id} AND competencias.tipo_competencia = " . $tipo_competencia);

        return $query;
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

    function get_datatables($id, $tipo_competencia)
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);


        $query = $this->db->query("
								SELECT competencias.id, competencias.nome FROM `avaliador_avaliados` 
									LEFT JOIN funcionarios_cargos ON avaliador_avaliados.avaliado = funcionarios_cargos.id_usuario
									LEFT JOIN competencias ON competencias.id_cargo = funcionarios_cargos.id_cargo
									LEFT JOIN comportamento_dimensao ON comportamento_dimensao.id_competencia = competencias.id
										WHERE 
											competencias.tipo_competencia = {$tipo_competencia}
											
											AND avaliado = {$id} 
												GROUP BY competencias.id ");

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
