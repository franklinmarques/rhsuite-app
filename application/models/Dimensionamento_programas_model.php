<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_programas_model extends MY_Model
{
	protected static $table = 'dimensionamento_programas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_job' => 'required|is_natural_no_zero|max_length[11]',
		'id_executor' => 'required|is_natural_no_zero|max_length[11]',
		'volume_trabalho' => 'decimal|max_length[10]',
		'qtde_horas_disponiveis' => 'decimal|max_length[10]',
		'tipo_valor' => 'exact_length[1]',
		'tipo_mao_obra' => 'exact_length[1]',
		'unidades' => 'max_length[10]',
		'mao_obra' => 'max_length[10]',
		'carga_horaria_necessaria' => 'decimal|max_length[10]',
		'horario_inicio_projetado' => 'valid_time',
		'horario_termino_projetado' => 'valid_time',
		'horario_inicio_real' => 'valid_time',
		'horario_termino_real' => 'valid_time',
		'status' => 'required|exact_length[1]'
	];

}
