<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_ordem_servico_horarios_model extends MY_Model
{
	protected static $table = 'ei_ordem_servico_horarios';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_os_profissional' => 'required|is_natural_no_zero|max_length[11]',
		'id_funcao' => 'is_natural_no_zero|max_length[11]',
		'id_os_profissional_sub1' => 'integer|max_length[11]',
		'id_funcao_sub1' => 'integer|max_length[11]',
		'data_substituicao1' => 'valid_date',
		'id_os_profissional_sub2' => 'integer|max_length[11]',
		'id_funcao_sub2' => 'integer|max_length[11]',
		'data_substituicao2' => 'valid_date',
		'dia_semana' => 'integer|max_length[1]',
		'periodo' => 'numeric|max_length[1]',
		'horario_inicio' => 'valid_time',
		'horario_termino' => 'valid_time',
		'total_dias_mes1' => 'integer|max_length[1]',
		'total_dias_mes2' => 'integer|max_length[1]',
		'total_dias_mes3' => 'integer|max_length[1]',
		'total_dias_mes4' => 'integer|max_length[1]',
		'total_dias_mes5' => 'integer|max_length[1]',
		'total_dias_mes6' => 'integer|max_length[1]',
		'valor_hora' => 'decimal|max_length[11]',
		'horas_diarias' => 'decimal|max_length[6]',
		'qtde_dias' => 'decimal|max_length[5]',
		'horas_semanais' => 'decimal|max_length[6]',
		'qtde_semanas' => 'integer|max_length[1]',
		'horas_mensais' => 'decimal|max_length[7]',
		'horas_semestre' => 'decimal|max_length[7]',
		'valor_hora_mensal' => 'decimal|max_length[11]',
		'valor_hora_operacional' => 'decimal|max_length[11]',
		'horas_mensais_custo' => 'valid_time',
		'data_inicio_contrato' => 'valid_date',
		'data_termino_contrato' => 'valid_date',
		'desconto_mensal_1' => 'decimal|max_length[7]',
		'desconto_mensal_2' => 'decimal|max_length[7]',
		'desconto_mensal_3' => 'decimal|max_length[7]',
		'desconto_mensal_4' => 'decimal|max_length[7]',
		'desconto_mensal_5' => 'decimal|max_length[7]',
		'desconto_mensal_6' => 'decimal|max_length[7]',
		'valor_mensal_1' => 'decimal|max_length[11]',
		'valor_mensal_2' => 'decimal|max_length[11]',
		'valor_mensal_3' => 'decimal|max_length[11]',
		'valor_mensal_4' => 'decimal|max_length[11]',
		'valor_mensal_5' => 'decimal|max_length[11]',
		'valor_mensal_6' => 'decimal|max_length[11]'
	];

}
