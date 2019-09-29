<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_observacoes_model extends MY_Model
{
    protected static $table = 'st_observacoes';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
        'total_colaboradores_contratados' => 'is_natural|max_length[11]',
        'total_colaboradores_ativos' => 'is_natural|max_length[11]',
        'visitas_projetadas' => 'is_natural|max_length[11]',
        'visitas_realizadas' => 'is_natural|max_length[11]',
        'visitas_percentagem' => 'is_natural|max_length[11]',
        'visitas_total_horas' => 'is_natural|max_length[11]',
        'balanco_valor_projetado' => 'numeric|max_length[11]',
        'balanco_glosas' => 'numeric|max_length[11]',
        'balanco_valor_glosa' => 'numeric|max_length[11]',
        'balanco_percentagem' => 'numeric|max_length[4]',
        'turnover_admissoes' => 'is_natural|max_length[11]',
        'turnover_demissoes' => 'is_natural|max_length[11]',
        'turnover_desligamentos' => 'is_natural|max_length[11]',
        'atendimentos_total_mes' => 'is_natural|max_length[11]',
        'atendimentos_media_diaria' => 'is_natural|max_length[11]',
        'pendencias_total_informada' => 'is_natural|max_length[11]',
        'pendencias_aguardando_tratativa' => 'is_natural|max_length[11]',
        'pendencias_parcialmente_resolvidas' => 'is_natural|max_length[11]',
        'pendencias_resolvidas' => 'is_natural|max_length[11]',
        'pendencias_resolvidas_atendimentos' => 'is_natural|max_length[11]',
        'monitoria_media_equipe' => 'is_natural|max_length[11]',
        'indicadores_operacinais_tma' => 'valid_time',
        'indicadores_operacinais_tme' => 'valid_time',
        'indicadores_operacinais_ociosidade' => 'valid_time',
        'avaliacoes_atendimento' => 'is_natural|max_length[11]',
        'avaliacoes_atendimento_otimos' => 'is_natural|max_length[11]',
        'avaliacoes_atendimento_bons' => 'is_natural|max_length[11]',
        'avaliacoes_atendimento_regulares' => 'is_natural|max_length[11]',
        'avaliacoes_atendimento_ruins' => 'is_natural|max_length[11]',
        'solicitacoes' => 'is_natural|max_length[11]',
        'solicitacoes_atendidas' => 'is_natural|max_length[11]',
        'solicitacoes_nao_atendidas' => 'is_natural|max_length[11]',
        'observacoes' => 'max_length[65535]'
    ];

}
