<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_alocacao_model extends MY_Model
{
	protected static $table = 'ei_alocacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'depto' => 'required|max_length[255]',
		'id_diretoria' => 'required|integer|max_length[11]',
		'diretoria' => 'required|max_length[255]',
		'id_supervisor' => 'required|integer|max_length[11]',
		'supervisor' => 'required|max_length[255]',
		'municipio' => 'required|max_length[255]',
		'coordenador' => 'required|max_length[255]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'semestre' => 'required|numeric|max_length[1]',
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
		'saldo_acumulado_mes7' => 'max_length[10]',
		'observacoes_mes1' => 'max_length[65535]',
		'observacoes_mes2' => 'max_length[65535]',
		'observacoes_mes3' => 'max_length[65535]',
		'observacoes_mes4' => 'max_length[65535]',
		'observacoes_mes5' => 'max_length[65535]',
		'observacoes_mes6' => 'max_length[65535]',
		'observacoes_mes7' => 'max_length[65535]'
	];

}
