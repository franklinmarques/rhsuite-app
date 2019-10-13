<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_carga_horaria_model extends MY_Model
{
	protected static $table = 'ei_carga_horaria';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisao' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'valid_date',
		'horario_entrada' => 'valid_time',
		'horario_saida' => 'valid_time',
		'horario_entrada_1' => 'valid_time',
		'horario_saida_1' => 'valid_time',
		'total' => 'valid_time',
		'carga_horaria' => 'valid_time',
		'saldo_dia' => 'valid_time',
		'observacoes' => 'max_length[65535]'
	];

}
