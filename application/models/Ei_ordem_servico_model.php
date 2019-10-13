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

}
