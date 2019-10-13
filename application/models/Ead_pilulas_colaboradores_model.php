<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_pilulas_colaboradores_model extends MY_Model
{
	protected static $table = 'cursos_pilulas_colaboradores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_pilula' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]'
	];

}
