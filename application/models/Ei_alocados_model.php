<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_alocados_model extends MY_Model
{
	protected static $table = 'ei_alocados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao_escola' => 'required|is_natural_no_zero|max_length[11]',
		'id_os_profissional' => 'is_natural_no_zero|max_length[11]',
		'id_cuidador' => 'is_natural_no_zero|max_length[11]',
		'cuidador' => 'max_length[255]',
		'valor_hora' => 'decimal|max_length[11]',
		'valor_hora_operacional' => 'decimal|max_length[11]',
		'valor_hora_pagamento' => 'decimal|max_length[11]',
		'horas_diarias' => 'decimal|max_length[6]',
		'horas_semanais' => 'decimal|max_length[6]',
		'qtde_dias' => 'decimal|max_length[5]',
		'horas_semestre' => 'decimal|max_length[7]',
		'total_dias_letivos' => 'required|integer|max_length[3]',
		'data_inicio_contrato' => 'valid_date',
		'data_termino_contrato' => 'valid_date',
		'horas_mensais_custo' => 'valid_time',
		'valor_total' => 'decimal|max_length[11]'
	];

}
