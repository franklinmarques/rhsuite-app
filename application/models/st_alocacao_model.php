<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_alocacao_model extends MY_Model
{
	protected static $table = 'alocacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'depto' => 'required|max_length[255]',
		'area' => 'required|max_length[255]',
		'setor' => 'required|max_length[255]',
		'contrato' => 'max_length[255]',
		'descricao_servico' => 'max_length[255]',
		'valor_servico' => 'decimal|max_length[11]',
		'dia_fechamento' => 'integer|max_length[2]',
		'qtde_alocados_potenciais' => 'integer|max_length[11]',
		'qtde_alocados_ativos' => 'integer|max_length[11]',
		'turnover_reposicao' => 'integer|max_length[11]',
		'turnover_aumento_quadro' => 'integer|max_length[11]',
		'turnover_desligamento_empresa' => 'integer|max_length[11]',
		'turnover_desligamento_colaborador' => 'integer|max_length[11]',
		'observacoes' => 'max_length[65535]',
		'valor_projetado' => 'decimal|max_length[11]',
		'valor_realizado' => 'decimal|max_length[11]',
		'total_faltas' => 'required|decimal|max_length[11]',
		'total_dias_cobertos' => 'required|decimal|max_length[11]',
		'total_dias_descobertos' => 'required|decimal|max_length[11]',
		'mes_bloqueado' => 'numeric|max_length[1]'
	];

}
