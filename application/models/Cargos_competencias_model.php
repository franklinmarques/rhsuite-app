<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos_competencias_model extends MY_Model
{
	protected static $table = 'cargos_competencias';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'id_cargo' => 'required|is_natural_no_zero|max_length[11]',
		'tipo_competencia' => 'required|exact_length[1]',
		'peso' => 'required|integer|max_length[11]',
		'id_modelo' => 'is_natural_no_zero|max_length[11]'
	];

}
