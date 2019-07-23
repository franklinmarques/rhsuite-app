<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StObservacoes extends Entity
{
    protected $id_alocacao;
    protected $total_colaboradores_contratados;
    protected $total_colaboradores_ativos;
    protected $visitas_projetadas;
    protected $visitas_realizadas;
    protected $visitas_porcentagem;
    protected $visitas_total_horas;
    protected $balanco_valor_projetado;
    protected $balanco_glosas;
    protected $balanco_valor_glosa;
    protected $balanco_porcentagem;
    protected $turnover_admissoes;
    protected $turnover_demissoes;
    protected $turnover_desligamentos;
    protected $atendimentos_total_mes;
    protected $atendimentos_media_diaria;
    protected $pendencias_total_informada;
    protected $pendencias_aguardando_tratativa;
    protected $pendencias_parcialmente_resolvidas;
    protected $pendencias_resolvidas;
    protected $pendencias_resolvidas_atendimentos;
    protected $monitoria_media_equipe;
    protected $indicadores_operacionais_tma;
    protected $indicadores_operacionais_tme;
    protected $indicadores_operacionais_ociosidade;
    protected $avaliacoes_atendimento;
    protected $avaliacoes_atendimento_otimos;
    protected $avaliacoes_atendimento_bons;
    protected $avaliacoes_atendimento_regulares;
    protected $avaliacoes_atendimento_ruins;
    protected $solicitacoes;
    protected $solicitacoes_atendidas;
    protected $solicitacoes_nao_atendidas;
    protected $observacoes;

    protected $casts = [
        'id_alocacao' => 'int',
        'total_colaboradores_contratados' => '?int',
        'total_colaboradores_ativos' => '?int',
        'visitas_projetadas' => '?int',
        'visitas_realizadas' => '?int',
        'visitas_porcentagem' => '?int',
        'visitas_total_horas' => '?int',
        'balanco_valor_projetado' => '?float',
        'balanco_glosas' => '?float',
        'balanco_valor_glosa' => '?float',
        'balanco_porcentagem' => '?float',
        'turnover_admissoes' => '?int',
        'turnover_demissoes' => '?int',
        'turnover_desligamentos' => '?int',
        'atendimentos_total_mes' => '?int',
        'atendimentos_media_diaria' => '?int',
        'pendencias_total_informada' => '?int',
        'pendencias_aguardando_tratativa' => '?int',
        'pendencias_parcialmente_resolvidas' => '?int',
        'pendencias_resolvidas' => '?int',
        'pendencias_resolvidas_atendimentos' => '?int',
        'monitoria_media_equipe' => '?int',
        'indicadores_operacionais_tma' => '?string',
        'indicadores_operacionais_tme' => '?string',
        'indicadores_operacionais_ociosidade' => '?string',
        'avaliacoes_atendimento' => '?int',
        'avaliacoes_atendimento_otimos' => '?int',
        'avaliacoes_atendimento_bons' => '?int',
        'avaliacoes_atendimento_regulares' => '?int',
        'avaliacoes_atendimento_ruins' => '?int',
        'solicitacoes' => '?int',
        'solicitacoes_atendidas' => '?int',
        'solicitacoes_nao_atendidas' => '?int',
        'observacoes' => '?string'
    ];

}
