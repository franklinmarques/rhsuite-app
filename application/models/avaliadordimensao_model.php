<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliadordimensao_model extends CI_Model
{

    var $table = 'avaliacao_resultado';
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
										competencias.nome as nome_competencia, 
										comportamento_dimensao.nome as nome_dimensao,
										comportamento_dimensao.id as id_dimensao
											FROM cargos 
												LEFT JOIN competencias ON cargos.id = competencias.id_cargo
												LEFT JOIN comportamento_dimensao ON comportamento_dimensao.id_competencia = competencias.id
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

    function get_datatables($id_competencia, $id_avaliador, $id_avaliado)
    {
        $sql = "	SELECT
							avaliacao_resultado.id,
							comportamento_dimensao.id as id_dimensao,
							comportamento_dimensao.nome,
							comportamento_dimensao.peso,
							avaliacao_resultado.nivel,
							avaliacao_resultado.atitude
								FROM avaliacao_resultado
									LEFT JOIN comportamento_dimensao ON comportamento_dimensao.id = avaliacao_resultado.id_dimensao
									LEFT JOIN competencias ON competencias.id = comportamento_dimensao.id_competencia
						WHERE competencias.id = " . $id_competencia . " AND avaliado = " . $id_avaliado . " AND avaliador = " . $id_avaliador;

        $resComportamentoDimensao = $this->db->query($sql);

        return $resComportamentoDimensao->result();
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
        $res = $this->db->query("SELECT comportamento_dimensao.id AS id_comp_dim, 
                    comportamento_dimensao.nome AS nome_dimensao, 
                    comportamento_dimensao.peso,
                    avaliacao_resultado.atitude, 
                    avaliacao_resultado.nivel,
                    avaliacao_resultado.id
                 FROM comportamento_dimensao
                                 LEFT JOIN avaliacao_resultado ON 
                                           avaliacao_resultado.id_dimensao = comportamento_dimensao.id
                                 WHERE avaliacao_resultado.id = {$id}");
        $return = $res->row();
        return $return;
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

    public function delete_by_id_avaliacao($id_avaliacao, $avaliado, $avaliador)
    {
        $this->db->query("DELETE FROM avaliacao_resultado WHERE id_avaliacao = $id_avaliacao AND avaliado =  $avaliado AND avaliador = $avaliador");
        return $this->db->affected_rows();
    }

}
