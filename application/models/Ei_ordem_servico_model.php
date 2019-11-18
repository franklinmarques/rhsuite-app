<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_ordem_servico_model extends MY_Model
{
	protected static $table = 'ei_ordem_servico';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_contrato' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'numero_empenho' => 'max_length[255]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'semestre' => 'required|numeric|max_length[1]'
	];

	//==========================================================================
	public function prepararNaoAlocados($where = [], $associative = true)
	{
		$data = $this->db
			->select('a.*', false)
			->join('ei_contratos b', 'b.id = a.id_contrato')
			->join('ei_diretorias c', 'c.id = b.id_cliente')
			->join('ei_alocacao d', 'd.ano = a.ano AND d.semestre = a.semestre');

		return $data;
	}

}
