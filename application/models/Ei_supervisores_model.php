<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_supervisores_model extends MY_Model
{
	protected static $table = 'ei_supervisores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_escola' => 'required|is_natural_no_zero|max_length[11]',
		'id_coordenacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisor' => 'required|is_natural_no_zero|max_length[11]',
		'turno' => 'exact_length[1]'
	];

}
