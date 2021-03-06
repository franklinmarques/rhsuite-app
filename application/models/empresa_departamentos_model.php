<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa_departamentos_model extends MY_Model
{
	protected static $table = 'empresa_departamentos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]'
	];

}
