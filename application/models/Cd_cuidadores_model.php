<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_cuidadores_model extends MY_Model
{
	protected static $table = 'cd_cuidadores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_cuidador' => 'required|is_natural_no_zero|max_length[11]',
		'id_escola' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisor' => 'is_natural_no_zero|max_length[11]',
		'turno' => 'required|exact_length[1]'
	];

}
