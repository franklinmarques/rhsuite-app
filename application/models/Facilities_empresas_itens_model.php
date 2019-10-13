<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_empresas_itens_model extends MY_Model
{
	protected static $table = 'facilities_empresas_itens';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_facility_empresa' => 'is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]',
		'ativo' => 'required|numeric|max_length[1]'
	];

}
