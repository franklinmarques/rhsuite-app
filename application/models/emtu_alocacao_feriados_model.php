<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emtu_alocacao_feriados_model extends MY_Model
{
	protected static $table = 'emtu_alocacao_feriados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'status' => 'required|exact_length[2]',
		'qtde_novos_processos' => 'integer|max_length[11]',
		'qtde_analistas' => 'integer|max_length[11]',
		'qtde_processos_tratados_dia' => 'integer|max_length[11]',
		'qtde_pagamentos' => 'integer|max_length[11]'
	];

}
