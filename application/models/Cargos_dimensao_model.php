<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos_dimensao_model extends MY_Model
{
	protected static $table = 'cargos_dimensao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'cargo_competencia' => 'required|is_natural_no_zero|max_length[11]',
		'nivel' => 'required|integer|max_length[11]',
		'peso' => 'required|integer|max_length[11]',
		'atitude' => 'required|integer|max_length[11]',
		'id_dimensao' => 'is_natural_no_zero|max_length[11]'
	];

}
