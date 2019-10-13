<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_salas_model extends MY_Model
{
	protected static $table = 'facilities_salas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_andar' => 'required|is_natural_no_zero|max_length[11]',
		'sala' => 'required|max_length[40]'
	];

}
