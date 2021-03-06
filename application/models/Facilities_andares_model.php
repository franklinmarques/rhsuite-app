<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_andares_model extends MY_Model
{
	protected static $table = 'facilities_andares';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_unidade' => 'required|is_natural_no_zero|max_length[11]',
		'andar' => 'required|max_length[20]'
	];

}
