<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_empresas_revisoes_model extends MY_Model
{
	protected static $table = 'facilities_empresas_revisoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_item' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]',
		'tipo' => 'required|exact_length[1]'
	];

}
