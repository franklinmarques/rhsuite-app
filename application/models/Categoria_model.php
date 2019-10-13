<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_model extends MY_Model
{
	protected static $table = 'categoria';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'curso' => 'required|max_length[255]'
	];

}
