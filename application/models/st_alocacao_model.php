<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_alocacao_model extends MY_Model
{
    protected static $table = 'st_alocacao';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'depto' => 'required|max_length[255]',
        'area' => 'required|max_length[255]',
        'setor' => 'required|max_length[255]',
        'data' => 'required|valid_date',
        'contrato' => 'max_length[255]',
        'descricao_servico' => 'max_length[255]',
        'valor_servico' => 'numeric|max_length[11]',
        'dia_fechamento' => 'is_natural|less_than_equal_to[31]',
        'qtde_alocados_potenciais' => 'is_natural|max_length[11]',
        'qtde_alocados_ativos' => 'is_natural|max_length[11]',
        'turnover_reposicao' => 'is_natural|max_length[11]',
        'turnover_aumento_quadro' => 'is_natural|max_length[11]',
        'turnover_desligamento_empresa' => 'is_natural|max_length[11]',
        'turnover_desligamento_colaborador' => 'is_natural|max_length[11]',
        'observacoes' => 'max_length[65535]',
        'valor_projetado' => 'numeric|max_length[11]',
        'valor_realizado' => 'numeric|max_length[11]',
        'total_faltas' => 'required|numeric|max_length[11]',
        'total_dias_cobertos' => 'required|numeric|max_length[11]',
        'total_dias_descobertos' => 'required|numeric|max_length[11]',
        'mes_bloqueado' => 'in_list[0,1]'
    ];

}
