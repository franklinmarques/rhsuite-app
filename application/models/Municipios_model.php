<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Municipios_model extends MY_Model
{
	protected static $table = 'municipios';

	protected static $primaryKey = 'cod_mun';

	protected static $autoIncrement = false;

	protected $validationRules = [
		'cod_mun' => 'required|is_natural_no_zero|max_length[11]',
		'cod_uf' => 'required|integer|max_length[11]',
		'municipio' => 'required|max_length[30]'
	];

}
