<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Deficiencias_model extends MY_Model
{
	protected static $table = 'deficiencias';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'required|max_length[50]'
	];

}
