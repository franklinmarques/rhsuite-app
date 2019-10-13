<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_coordenacao_model extends MY_Model
{
	protected static $table = 'ei_coordenacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'depto' => 'required|integer|max_length[11]',
		'area' => 'required|integer|max_length[11]',
		'setor' => 'required|integer|max_length[11]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'semestre' => 'required|numeric|max_length[1]',
		'carga_horaria' => 'valid_time',
		'saldo_acumulado_horas' => 'max_length[10]',
		'is_coordenador' => 'numeric|max_length[1]',
		'is_supervisor' => 'numeric|max_length[1]'
	];

}
