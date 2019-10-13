<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_alocados_model extends MY_Model
{
	protected static $table = 'cd_alocados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_vinculado' => 'integer|max_length[11]',
		'cuidador' => 'max_length[255]',
		'escola' => 'max_length[255]',
		'municipio' => 'max_length[100]',
		'supervisor' => 'max_length[255]',
		'turno' => 'exact_length[1]',
		'dia_inicial' => 'integer|max_length[2]',
		'dia_limite' => 'integer|max_length[2]',
		'remanejado' => 'integer|max_length[1]'
	];

}
