<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_frequencias_model extends MY_Model
{
	protected static $table = 'cd_frequencias';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_matriculado' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'status' => 'exact_length[2]'
	];

}
