<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_alocados_model extends MY_Model
{
	protected static $table = 'alocacao_usuarios';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'cargo' => 'max_length[255]',
		'funcao' => 'max_length[255]',
		'id_posto' => 'is_natural_no_zero|max_length[11]',
		'tipo_horario' => 'required|exact_length[1]',
		'nivel' => 'required|exact_length[1]',
		'tipo_bck' => 'exact_length[1]',
		'data_recesso' => 'valid_date',
		'data_retorno' => 'valid_date',
		'id_usuario_bck' => 'is_natural_no_zero|max_length[11]',
		'nome_bck' => 'max_length[255]',
		'data_desligamento' => 'valid_date',
		'id_usuario_sub' => 'is_natural_no_zero|max_length[11]',
		'nome_sub' => 'max_length[255]',
		'dias_acrescidos' => 'decimal|max_length[11]',
		'horas_acrescidas' => 'decimal|max_length[11]',
		'total_acrescido' => 'decimal|max_length[11]',
		'total_faltas' => 'valid_time',
		'total_atrasos' => 'valid_time',
		'horas_saldo' => 'valid_time',
		'horas_saldo_acumulado' => 'valid_time'
	];

}
