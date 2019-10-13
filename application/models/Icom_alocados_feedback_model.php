<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_alocados_feedback_model extends MY_Model
{
	protected static $table = 'icom_alocados_feedback';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocado' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario_orientador' => 'is_natural_no_zero|max_length[11]',
		'nome_usuario_orientador' => 'required|max_length[255]',
		'data' => 'required|valid_date',
		'descricao' => 'max_length[65535]',
		'resultado' => 'max_length[65535]'
	];

}
