<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emtu_alocacao_model extends MY_Model
{
	protected static $table = 'emtu_alocacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'is_natural_no_zero|max_length[11]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'id_setor' => 'is_natural_no_zero|max_length[11]',
		'mes' => 'required|integer|max_length[2]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'dia_fechamento' => 'integer|max_length[2]'
	];

}
