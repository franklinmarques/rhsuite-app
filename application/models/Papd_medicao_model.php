<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Papd_medicao_model extends MY_Model
{
	protected static $table = 'papd_medicao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'mes' => 'required|integer|max_length[2]',
		'total_pacientes_cadastrados' => 'required|integer|max_length[11]',
		'total_pacientes_inativos' => 'required|integer|max_length[11]',
		'total_pacientes_monitorados' => 'required|integer|max_length[11]'
	];

}
