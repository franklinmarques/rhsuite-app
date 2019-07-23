<?php

namespace App\Entities;

use CodeIgniter\Entity;

class ViewAlocacao2 extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $data;
    protected $id_empresa;
    protected $id_usuario;
    protected $nome;
    protected $depto;
    protected $area;
    protected $setor;
    protected $qtde_alocados_potenciais;
    protected $total_faltas;
    protected $total_dias_cobertos;
    protected $total_dias_descobertos;
    protected $valor_projetado;
    protected $valor_realizado;
    protected $turnover_reposicao;
    protected $turnover_aumento_quadro;
    protected $turnover_desligamento_empresa;
    protected $turnover_desligamento_colaborador;
    protected $cargo;
    protected $funcao;
    protected $status;
    protected $id_usuario_bck;
    protected $id_usuario_sub;
    protected $data_recesso;
    protected $data_retorno;
    protected $qtde_dias;
    protected $tempo_atraso;
    protected $qtde_dias_cobertos;
    protected $qtde_faltas;
    protected $dias_ausentes;
    protected $horas_atraso;
    protected $minutos_atraso;
    protected $saldo;
    protected $segundos_atraso;
    protected $posto_descoberto;
    protected $valor_posto;
    protected $valor_dia;
    protected $total_dias_mensais;
    protected $valor_hora;
    protected $total_horas_diarias;
    protected $dias_acrescidos;
    protected $horas_acrescidas;
    protected $total_acrescido;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'data' => 'datetime',
        'id_empresa' => 'int',
        'id_usuario' => 'int',
        'nome' => 'string',
        'depto' => 'string',
        'area' => 'string',
        'setor' => 'string',
        'qtde_alocados_potenciais' => '?int',
        'total_faltas' => 'float',
        'total_dias_cobertos' => 'float',
        'total_dias_descobertos' => 'float',
        'valor_projetado' => '?float',
        'valor_realizado' => '?float',
        'turnover_reposicao' => '?int',
        'turnover_aumento_quadro' => '?int',
        'turnover_desligamento_empresa' => '?int',
        'turnover_desligamento_colaborador' => '?int',
        'cargo' => '?string',
        'funcao' => '?string',
        'status' => '?int',
        'id_usuario_bck' => '?int',
        'id_usuario_sub' => '?int',
        'data_recesso' => '?datetime',
        'data_retorno' => '?datetime',
        'qtde_dias' => '?float',
        'tempo_atraso' => '?string',
        'qtde_dias_cobertos' => '?float',
        'qtde_faltas' => '?float',
        'dias_ausentes' => '?int',
        'horas_atraso' => '?float',
        'minutos_atraso' => '?float',
        'saldo' => 'float',
        'segundos_atraso' => '?float',
        'posto_descoberto' => '?float',
        'valor_posto' => '?float',
        'valor_dia' => '?float',
        'total_dias_mensais' => '?int',
        'valor_hora' => '?float',
        'total_horas_diarias' => '?int',
        'dias_acrescidos' => '?float',
        'horas_acrescidas' => '?float',
        'total_acrescido' => '?float'
    ];

}
