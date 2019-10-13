<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_observacoes_model extends MY_Model
{
	protected static $table = 'cd_observacoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisor' => 'is_natural_no_zero|max_length[11]',
		'supervisor' => 'required|max_length[255]',
		'total_faltas' => 'integer|max_length[2]',
		'total_faltas_justificadas' => 'integer|max_length[2]',
		'turnover_substituicao' => 'integer|max_length[11]',
		'turnover_aumento_quadro' => 'integer|max_length[11]',
		'turnover_desligamento_empresa' => 'integer|max_length[11]',
		'turnover_desligamento_solicitacao' => 'integer|max_length[11]',
		'intercorrencias_diretoria' => 'integer|max_length[11]',
		'intercorrencias_cuidador' => 'integer|max_length[11]',
		'intercorrencias_alunos' => 'integer|max_length[11]',
		'acidentes_trabalho' => 'integer|max_length[11]',
		'total_escolas' => 'integer|max_length[11]',
		'total_alunos' => 'integer|max_length[11]',
		'dias_letivos' => 'integer|max_length[2]',
		'total_cuidadores' => 'integer|max_length[11]',
		'total_cuidadores_cobrados' => 'integer|max_length[11]',
		'total_cuidadores_ativos' => 'integer|max_length[11]',
		'total_cuidadores_afastados' => 'integer|max_length[11]',
		'total_supervisores' => 'integer|max_length[11]',
		'total_supervisores_cobrados' => 'integer|max_length[11]',
		'total_supervisores_ativos' => 'integer|max_length[11]',
		'total_supervisores_afastados' => 'integer|max_length[11]',
		'faturamento_projetado' => 'decimal|max_length[11]',
		'faturamento_realizado' => 'decimal|max_length[11]'
	];

}
