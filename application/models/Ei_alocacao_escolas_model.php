<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_alocacao_escolas_model extends MY_Model
{
	protected static $table = 'ei_alocacao_escolas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_os_escola' => 'is_natural_no_zero|max_length[11]',
		'id_escola' => 'is_natural_no_zero|max_length[11]',
		'codigo' => 'integer|max_length[4]',
		'escola' => 'required|max_length[255]',
		'municipio' => 'required|max_length[255]',
		'ordem_servico' => 'required|max_length[255]',
		'contrato' => 'required|max_length[30]'
	];

}
