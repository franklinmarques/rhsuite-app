<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emtu_apontamento_model extends MY_Model
{
	protected static $table = 'emtu_apontamento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocado' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'horario_entrada' => 'valid_datetime',
		'horario_intervalo' => 'valid_datetime',
		'horario_retorno' => 'valid_datetime',
		'horario_saida' => 'valid_datetime',
		'qtde_dias' => 'integer|max_length[2]',
		'hora_atraso' => 'valid_time',
		'hora_extra' => 'valid_time',
		'desconto_folha' => 'valid_time',
		'saldo_banco_horas' => 'valid_time',
		'hora_glosa' => 'valid_time',
		'observacoes' => 'max_length[4294967295]',
		'status' => 'required|exact_length[2]',
		'id_alocado_bck' => 'is_natural_no_zero|max_length[11]'
	];

}
