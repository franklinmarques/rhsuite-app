<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_modelos_model extends MY_Model
{
	protected static $table = 'facilities_modelos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_facility_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'tipo' => 'exact_length[1]',
		'versao' => 'required|max_length[255]',
		'status' => 'required|numeric|max_length[1]',
		'id_copia' => 'is_natural_no_zero|max_length[11]'
	];

}
