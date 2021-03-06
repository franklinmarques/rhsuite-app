<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa_setores_model extends MY_Model
{
	protected static $table = 'empresa_setores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'cnpj' => 'max_length[18]'
	];

}
