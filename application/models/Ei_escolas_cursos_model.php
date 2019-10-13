<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_escolas_cursos_model extends MY_Model
{
	protected static $table = 'ei_escolas_cursos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_escola' => 'required|is_natural_no_zero|max_length[11]',
		'id_curso' => 'required|is_natural_no_zero|max_length[11]',
		'id_diretoria_curso' => 'is_natural_no_zero|max_length[11]'
	];

}
