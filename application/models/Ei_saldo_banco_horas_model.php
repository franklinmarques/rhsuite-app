<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_saldo_banco_horas_model extends MY_Model
{
	protected static $table = 'ei_saldo_banco_horas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisao' => 'required|is_natural_no_zero|max_length[11]',
		'saldo_mes1' => 'max_length[10]',
		'saldo_mes2' => 'max_length[10]',
		'saldo_mes3' => 'max_length[10]',
		'saldo_mes4' => 'max_length[10]',
		'saldo_mes5' => 'max_length[10]',
		'saldo_mes6' => 'max_length[10]',
		'saldo_mes7' => 'max_length[10]',
		'saldo_acumulado_mes1' => 'max_length[10]',
		'saldo_acumulado_mes2' => 'max_length[10]',
		'saldo_acumulado_mes3' => 'max_length[10]',
		'saldo_acumulado_mes4' => 'max_length[10]',
		'saldo_acumulado_mes5' => 'max_length[10]',
		'saldo_acumulado_mes6' => 'max_length[10]',
		'saldo_acumulado_mes7' => 'max_length[10]'
	];

}
