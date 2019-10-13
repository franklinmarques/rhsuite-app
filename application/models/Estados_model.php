<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estados_model extends MY_Model
{
	protected static $table = 'estados';

	protected static $primaryKey = 'cod_uf';

	protected static $autoIncrement = false;

	protected $validationRules = [
		'cod_uf' => 'required|is_natural_no_zero|max_length[2]',
		'estado' => 'required|max_length[30]',
		'uf' => 'required|exact_length[2]'
	];

}
