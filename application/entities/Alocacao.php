<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Alocacao extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $data;
    protected $depto;
    protected $area;
    protected $setor;
    protected $contrato;
    protected $descricao_servico;
    protected $valor_servico;
    protected $dia_fechamento;
    protected $qtde_alocados_potenciais;
    protected $qtde_alocados_ativos;
    protected $turnover_reposicao;
    protected $turnover_aumento_quadro;
    protected $turnover_desligamento_empresa;
    protected $turnover_desligamento_colaborador;
    protected $observacoes;
    protected $valor_projetado;
    protected $valor_realizado;
    protected $total_faltas;
    protected $total_dias_cobertos;
    protected $total_dias_descobertos;
    protected $mes_bloqueado;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'data' => 'datetime',
        'depto' => 'string',
        'area' => 'string',
        'setor' => 'string',
        'contrato' => '?string',
        'descricao_servico' => '?string',
        'valor_servico' => '?float',
        'dia_fechamento' => '?int',
        'qtde_alocados_potenciais' => '?int',
        'qtde_alocados_ativos' => '?int',
        'turnover_reposicao' => '?int',
        'turnover_aumento_quadro' => '?int',
        'turnover_desligamento_empresa' => '?int',
        'turnover_desligamento_colaborador' => '?int',
        'observacoes' => '?string',
        'valor_projetado' => '?float',
        'valor_realizado' => '?float',
        'total_faltas' => 'float',
        'total_dias_cobertos' => 'float',
        'total_dias_descobertos' => 'float',
        'mes_bloqueado' => '?int'
    ];

}
