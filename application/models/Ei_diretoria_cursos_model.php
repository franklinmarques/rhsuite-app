<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_diretoria_cursos_model extends MY_Model
{
	protected static $table = 'ei_diretoria_cursos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_diretoria' => 'required|integer|max_length[11]',
		'id_curso' => 'required|integer|max_length[11]'
	];

}
