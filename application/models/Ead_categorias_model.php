<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_categorias_model extends MY_Model
{
	protected static $table = 'cursos_categorias';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]'
	];

}
