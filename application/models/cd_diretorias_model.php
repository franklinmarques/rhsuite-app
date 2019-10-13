<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_diretorias_model extends MY_Model
{
	protected static $table = 'cd_diretorias';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[100]',
		'alias' => 'max_length[100]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'depto' => 'required|max_length[255]',
		'municipio' => 'required|max_length[100]',
		'contrato' => 'required|max_length[30]',
		'id_coordenador' => 'is_natural_no_zero|max_length[11]'
	];

}
