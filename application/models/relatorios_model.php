<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios_model extends CI_Model
{

    var $table = 'cargos';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_cargos($id)
    {
        $sql = "SELECT cargos.id,
                       cargos.id_usuario_EMPRESA,
                       cargos.nome,
                       usuarios.nome,
                       cargos.peso_competencias_tecnicas,
                       cargos.peso_competencias_comportamentais
		FROM cargos 
		LEFT JOIN funcionarios_cargos ON 
                          funcionarios_cargos.id_cargo = cargos.id 
		LEFT JOIN usuarios ON 
                          funcionarios_cargos.id_usuario = usuarios.id 
                WHERE funcionarios_cargos.id_usuario = " . $id;
        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_cargos_colaborador($id_avaliacao, $id_avaliado, $id_dimensao)
    {
        $sql = "SELECT * FROM avaliacao_resultado 
                WHERE id_avaliacao = $id_avaliacao AND 
                      avaliado = $id_avaliado AND 
                      id_dimensao = $id_dimensao";
        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_competencias($id, $tipo = null)
    {
        $this->db->where(array('id_cargo' => $id));
        $this->db->from('competencias');
        $query = $this->db->get();
        return $query->result();
    }

    function get_dimensao($id)
    {
        $this->db->where('id_competencia', $id);
        $this->db->from('comportamento_dimensao');
        $query = $this->db->get();
        return $query->result();
    }

}
