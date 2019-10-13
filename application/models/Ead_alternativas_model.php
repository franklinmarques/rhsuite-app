<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_alternativas_model extends MY_Model
{
	protected static $table = 'cursos_alternativas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_questao' => 'required|is_natural_no_zero|max_length[11]',
		'alternativa' => 'required|max_length[65535]',
		'peso' => 'required|integer|max_length[3]',
		'id_copia' => 'integer|max_length[11]'
	];

}
