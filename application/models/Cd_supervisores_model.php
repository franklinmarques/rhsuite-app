<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_supervisores_model extends MY_Model
{
	protected static $table = 'cd_supervisores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisor' => 'required|is_natural_no_zero|max_length[11]',
		'id_escola' => 'required|is_natural_no_zero|max_length[11]',
		'turno' => 'required|exact_length[1]'
	];

}
