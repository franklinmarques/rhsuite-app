<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escolaridade_model extends MY_Model
{
	protected static $table = 'escolaridade';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]'
	];

}
