<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_faturamento_model extends MY_Model
{
	protected static $table = 'ei_faturamento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_escola' => 'is_natural_no_zero|max_length[11]',
		'escola' => 'required|max_length[255]',
		'cargo' => 'required|max_length[255]',
		'funcao' => 'required|max_length[255]',
		'data_aprovacao_mes1' => 'valid_date',
		'data_aprovacao_mes2' => 'valid_date',
		'data_aprovacao_mes3' => 'valid_date',
		'data_aprovacao_mes4' => 'valid_date',
		'data_aprovacao_mes5' => 'valid_date',
		'data_aprovacao_mes6' => 'valid_date',
		'data_aprovacao_mes7' => 'valid_date',
		'data_aprovacao_sub1' => 'valid_date',
		'data_aprovacao_sub2' => 'valid_date',
		'data_impressao_mes1' => 'valid_date',
		'data_impressao_mes2' => 'valid_date',
		'data_impressao_mes3' => 'valid_date',
		'data_impressao_mes4' => 'valid_date',
		'data_impressao_mes5' => 'valid_date',
		'data_impressao_mes6' => 'valid_date',
		'data_impressao_mes7' => 'valid_date',
		'data_impressao_sub1' => 'valid_date',
		'data_impressao_sub2' => 'valid_date',
		'observacoes_mes1' => 'max_length[65535]',
		'observacoes_mes2' => 'max_length[65535]',
		'observacoes_mes3' => 'max_length[65535]',
		'observacoes_mes4' => 'max_length[65535]',
		'observacoes_mes5' => 'max_length[65535]',
		'observacoes_mes6' => 'max_length[65535]',
		'observacoes_mes7' => 'max_length[65535]',
		'observacoes_sub1' => 'max_length[65535]',
		'observacoes_sub2' => 'max_length[65535]'
	];

}
