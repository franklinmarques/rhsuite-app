<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_clientes_resultado_model extends MY_Model
{
	protected static $table = 'cursos_clientes_resultado';

	protected static $createdField = 'data_avaliacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_acesso' => 'required|is_natural_no_zero|max_length[11]',
		'id_questao' => 'required|is_natural_no_zero|max_length[11]',
		'id_alternativa' => 'is_natural_no_zero|max_length[11]',
		'valor' => 'integer|max_length[11]',
		'resposta' => 'max_length[4294967295]',
		'nota' => 'integer|max_length[3]',
		'data_avaliacao' => 'required|valid_datetime',
		'status' => 'required|integer|max_length[11]'
	];

}
