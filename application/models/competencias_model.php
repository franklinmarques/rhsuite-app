<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_model extends MY_Model
{
	protected static $table = 'competencias';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'id_usuario_EMPRESA' => 'required|is_natural_no_zero|max_length[11]',
		'id_cargo' => 'required|is_natural_no_zero|max_length[11]',
		'descricao' => 'required|max_length[4294967295]',
		'data_inicio' => 'required|valid_date',
		'data_termino' => 'required|valid_date|after_or_equal_date[data_inicio]',
		'status' => 'required|integer|max_length[1]'
	];

}
