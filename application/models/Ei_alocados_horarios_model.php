<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_alocados_horarios_model extends MY_Model
{
	protected static $table = 'ei_alocados_horarios';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocado' => 'required|is_natural_no_zero|max_length[11]',
		'id_os_horario' => 'is_natural_no_zero|max_length[11]',
		'cargo' => 'max_length[255]',
		'funcao' => 'max_length[255]',
		'dia_semana' => 'required|integer|max_length[1]',
		'periodo' => 'numeric|max_length[1]',
		'horario_inicio_mes1' => 'valid_time',
		'horario_inicio_mes2' => 'valid_time',
		'horario_inicio_mes3' => 'valid_time',
		'horario_inicio_mes4' => 'valid_time',
		'horario_inicio_mes5' => 'valid_time',
		'horario_inicio_mes6' => 'valid_time',
		'horario_inicio_mes7' => 'valid_time',
		'horario_termino_mes1' => 'valid_time',
		'horario_termino_mes2' => 'valid_time',
		'horario_termino_mes3' => 'valid_time',
		'horario_termino_mes4' => 'valid_time',
		'horario_termino_mes5' => 'valid_time',
		'horario_termino_mes6' => 'valid_time',
		'horario_termino_mes7' => 'valid_time',
		'total_horas_mes1' => 'valid_time',
		'total_horas_mes2' => 'valid_time',
		'total_horas_mes3' => 'valid_time',
		'total_horas_mes4' => 'valid_time',
		'total_horas_mes5' => 'valid_time',
		'total_horas_mes6' => 'valid_time',
		'total_horas_mes7' => 'valid_time',
		'total_semanas_mes1' => 'required|integer|max_length[1]',
		'total_semanas_mes2' => 'required|integer|max_length[1]',
		'total_semanas_mes3' => 'required|integer|max_length[1]',
		'total_semanas_mes4' => 'required|integer|max_length[1]',
		'total_semanas_mes5' => 'required|integer|max_length[1]',
		'total_semanas_mes6' => 'required|integer|max_length[1]',
		'total_semanas_mes7' => 'required|integer|max_length[1]',
		'desconto_mes1' => 'decimal|max_length[4]',
		'desconto_mes2' => 'decimal|max_length[4]',
		'desconto_mes3' => 'decimal|max_length[4]',
		'desconto_mes4' => 'decimal|max_length[4]',
		'desconto_mes5' => 'decimal|max_length[4]',
		'desconto_mes6' => 'decimal|max_length[4]',
		'desconto_mes7' => 'decimal|max_length[4]',
		'endosso_mes1' => 'decimal|max_length[4]',
		'endosso_mes2' => 'decimal|max_length[4]',
		'endosso_mes3' => 'decimal|max_length[4]',
		'endosso_mes4' => 'decimal|max_length[4]',
		'endosso_mes5' => 'decimal|max_length[4]',
		'endosso_mes6' => 'decimal|max_length[4]',
		'endosso_mes7' => 'decimal|max_length[4]',
		'endosso_sub1' => 'decimal|max_length[4]',
		'endosso_sub2' => 'decimal|max_length[4]',
		'total_mes1' => 'valid_time',
		'total_mes2' => 'valid_time',
		'total_mes3' => 'valid_time',
		'total_mes4' => 'valid_time',
		'total_mes5' => 'valid_time',
		'total_mes6' => 'valid_time',
		'total_mes7' => 'valid_time',
		'total_endossado_mes1' => 'valid_time',
		'total_endossado_mes2' => 'valid_time',
		'total_endossado_mes3' => 'valid_time',
		'total_endossado_mes4' => 'valid_time',
		'total_endossado_mes5' => 'valid_time',
		'total_endossado_mes6' => 'valid_time',
		'total_endossado_mes7' => 'valid_time',
		'total_endossado_sub1' => 'valid_time',
		'total_endossado_sub2' => 'valid_time',
		'id_cuidador_sub1' => 'is_natural_no_zero|max_length[11]',
		'cargo_sub1' => 'max_length[255]',
		'funcao_sub1' => 'max_length[255]',
		'data_substituicao1' => 'valid_date',
		'total_semanas_sub1' => 'integer|max_length[1]',
		'desconto_sub1' => 'decimal|max_length[4]',
		'total_sub1' => 'valid_time',
		'id_cuidador_sub2' => 'is_natural_no_zero|max_length[11]',
		'cargo_sub2' => 'max_length[255]',
		'funcao_sub2' => 'max_length[255]',
		'data_substituicao2' => 'valid_date',
		'total_semanas_sub2' => 'integer|max_length[1]',
		'desconto_sub2' => 'decimal|max_length[4]',
		'total_sub2' => 'valid_time',
		'data_inicio_contrato' => 'valid_date',
		'data_termino_contrato' => 'valid_date',
		'valor_hora_operacional' => 'decimal|max_length[11]',
		'horas_mensais_custo' => 'valid_time',
		'valor_hora_funcao' => 'decimal|max_length[11]',
		'data_inicio_real' => 'valid_date',
		'data_termino_real' => 'valid_date'
	];

}
