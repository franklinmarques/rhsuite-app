<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_sessoes_model extends MY_Model
{
	protected static $table = 'icom_sessoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_produto' => 'required|is_natural_no_zero|max_length[11]',
		'codigo_contrato' => 'required|is_natural_no_zero|max_length[11]',
		'data_evento' => 'required|valid_date',
		'horario_inicio' => 'required|valid_time',
		'horario_termino' => 'required|valid_time|after_time[horario_inicio]',
		'qtde_horas' => 'required|is_natural_no_zero|less_than_equal_to[24]',
		'local_evento' => 'max_length[65535]',
		'valor_faturamento' => 'numeric|max_length[11]',
		'valor_desconto' => 'numeric|max_length[11]',
		'custo_operacional' => 'numeric|max_length[11]',
		'custo_impostos' => 'numeric|max_length[11]',
		'profissional_alocado' => 'required|max_length[255]',
		'valor_pagamento_profissional' => 'numeric|max_length[11]',
		'observacoes' => 'max_length[65535]'
	];

}
