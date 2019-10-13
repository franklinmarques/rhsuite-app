<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_faltas_atrasos_model extends MY_Model
{
	protected static $table = 'usuarios_faltas_atrasos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_colaborador' => 'is_natural_no_zero|max_length[11]',
		'id_depto' => 'required|is_natural_no_zero|max_length[11]',
		'id_area' => 'required|is_natural_no_zero|max_length[11]',
		'id_setor' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'falta' => 'numeric|max_length[1]',
		'horas_atraso' => 'valid_time',
		'id_colaborador_sub' => 'is_natural_no_zero|max_length[11]',
		'status' => 'required|exact_length[2]',
		'observacoes' => 'max_length[65535]'
	];

}
