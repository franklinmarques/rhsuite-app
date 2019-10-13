<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_alocacao_observacoes_model extends MY_Model
{
	protected static $table = 'alocacao_observacoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'total_colaboradores_contratados' => 'integer|max_length[11]',
		'total_colaboradores_ativos' => 'integer|max_length[11]',
		'visitas_projetadas' => 'integer|max_length[11]',
		'visitas_realizadas' => 'integer|max_length[11]',
		'visitas_porcentagem' => 'integer|max_length[11]',
		'visitas_total_horas' => 'integer|max_length[11]',
		'balanco_valor_projetado' => 'decimal|max_length[11]',
		'balanco_glosas' => 'decimal|max_length[11]',
		'balanco_valor_glosa' => 'decimal|max_length[11]',
		'balanco_porcentagem' => 'decimal|max_length[4]',
		'turnover_admissoes' => 'integer|max_length[11]',
		'turnover_demissoes' => 'integer|max_length[11]',
		'turnover_desligamentos' => 'integer|max_length[11]',
		'atendimentos_total_mes' => 'integer|max_length[11]',
		'atendimentos_media_diaria' => 'integer|max_length[11]',
		'pendencias_total_informada' => 'integer|max_length[11]',
		'pendencias_aguardando_tratativa' => 'integer|max_length[11]',
		'pendencias_parcialmente_resolvidas' => 'integer|max_length[11]',
		'pendencias_resolvidas' => 'integer|max_length[11]',
		'pendencias_resolvidas_atendimentos' => 'integer|max_length[11]',
		'monitoria_media_equipe' => 'integer|max_length[11]',
		'indicadores_operacionais_tma' => 'valid_time',
		'indicadores_operacionais_tme' => 'valid_time',
		'indicadores_operacionais_ociosidade' => 'valid_time',
		'avaliacoes_atendimento' => 'integer|max_length[11]',
		'avaliacoes_atendimento_otimos' => 'integer|max_length[11]',
		'avaliacoes_atendimento_bons' => 'integer|max_length[11]',
		'avaliacoes_atendimento_regulares' => 'integer|max_length[11]',
		'avaliacoes_atendimento_ruins' => 'integer|max_length[11]',
		'solicitacoes' => 'integer|max_length[11]',
		'solicitacoes_atendidas' => 'integer|max_length[11]',
		'solicitacoes_nao_atendidas' => 'integer|max_length[11]',
		'observacoes' => 'max_length[65535]'
	];

}
