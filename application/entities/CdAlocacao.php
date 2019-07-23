<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdAlocacao extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $data;
    protected $depto;
    protected $diretoria;
    protected $coordenador;
    protected $municipio;
    protected $supervisor;
    protected $total_faltas;
    protected $total_faltas_justificadas;
    protected $turnover_substituicao;
    protected $turnover_aumento_quadro;
    protected $turnover_desligamento_empresa;
    protected $turnover_desligamento_solicitacao;
    protected $intercorrencias_diretoria;
    protected $intercorrencias_cuidador;
    protected $intercorrencias_alunos;
    protected $acidentes_trabalho;
    protected $total_escolas;
    protected $total_alunos;
    protected $dias_letivos;
    protected $total_cuidadores;
    protected $total_cuidadores_cobrados;
    protected $total_cuidadores_ativos;
    protected $total_cuidadores_afastados;
    protected $total_supervisores;
    protected $total_supervisores_cobrados;
    protected $total_supervisores_ativos;
    protected $total_supervisores_afastados;
    protected $faturamento_projetado;
    protected $faturamento_realizado;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'data' => 'datetime',
        'depto' => 'string',
        'diretoria' => 'string',
        'coordenador' => 'string',
        'municipio' => 'string',
        'supervisor' => 'string',
        'total_faltas' => '?int',
        'total_faltas_justificadas' => '?int',
        'turnover_substituicao' => '?int',
        'turnover_aumento_quadro' => '?int',
        'turnover_desligamento_empresa' => '?int',
        'turnover_desligamento_solicitacao' => '?int',
        'intercorrencias_diretoria' => '?int',
        'intercorrencias_cuidador' => '?int',
        'intercorrencias_alunos' => '?int',
        'acidentes_trabalho' => '?int',
        'total_escolas' => '?int',
        'total_alunos' => '?int',
        'dias_letivos' => '?int',
        'total_cuidadores' => '?int',
        'total_cuidadores_cobrados' => '?int',
        'total_cuidadores_ativos' => '?int',
        'total_cuidadores_afastados' => '?int',
        'total_supervisores' => '?int',
        'total_supervisores_cobrados' => '?int',
        'total_supervisores_ativos' => '?int',
        'total_supervisores_afastados' => '?int',
        'faturamento_projetado' => '?float',
        'faturamento_realizado' => '?float'
    ];

}
