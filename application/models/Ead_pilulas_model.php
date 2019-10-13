<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_pilulas_model extends MY_Model
{
	protected static $table = 'cursos_pilulas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_curso' => 'required|is_natural_no_zero|max_length[11]',
		'id_area_conhecimento' => 'is_natural_no_zero|max_length[11]',
		'publico' => 'required|numeric|max_length[1]'
	];

}
