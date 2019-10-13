<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_disciplinas_model extends MY_Model
{
	protected static $table = 'ei_disciplinas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_curso' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'qtde_semestres' => 'numeric|max_length[2]'
	];

}
