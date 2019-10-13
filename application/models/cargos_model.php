<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos_model extends MY_Model
{
	protected static $table = 'cargos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'cargo' => 'required|max_length[255]',
		'funcao' => 'required|max_length[255]',
		'id_usuario_EMPRESA' => 'required|is_natural_no_zero|max_length[11]',
		'peso_competencias_tecnicas' => 'required|integer|max_length[11]',
		'peso_competencias_comportamentais' => 'required|integer|max_length[11]'
	];

}
