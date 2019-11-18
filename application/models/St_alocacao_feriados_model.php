<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_alocacao_feriados_model extends MY_Model
{
	protected static $table = 'alocacao_feriados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'status' => 'in_list[FR,EM]',
		'qtde_novos_processos' => 'is_natural|max_length[11]',
		'qtde_analistas' => 'is_natural|max_length[11]',
		'qtde_processos_analisados' => 'is_natural|max_length[11]',
		'qtde_pagamentos' => 'is_natural|max_length[11]',
		'qtde_linhas_analisadas' => 'is_natural|max_length[11]'
	];

	protected static $status = ['FR' => 'Feriado', 'EM' => 'Emenda de feriado', '' => 'Nenhum'];
}
