<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiFaturamento extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_escola;
    protected $escola;
    protected $cargo;
    protected $funcao;
    protected $data_aprovacao_mes1;
    protected $data_aprovacao_mes2;
    protected $data_aprovacao_mes3;
    protected $data_aprovacao_mes4;
    protected $data_aprovacao_mes5;
    protected $data_aprovacao_mes6;
    protected $data_aprovacao_mes7;
    protected $data_aprovacao_sub1;
    protected $data_aprovacao_sub2;
    protected $data_impressao_mes1;
    protected $data_impressao_mes2;
    protected $data_impressao_mes3;
    protected $data_impressao_mes4;
    protected $data_impressao_mes5;
    protected $data_impressao_mes6;
    protected $data_impressao_mes7;
    protected $data_impressao_sub1;
    protected $data_impressao_sub2;
    protected $observacoes_mes1;
    protected $observacoes_mes2;
    protected $observacoes_mes3;
    protected $observacoes_mes4;
    protected $observacoes_mes5;
    protected $observacoes_mes6;
    protected $observacoes_mes7;
    protected $observacoes_sub1;
    protected $observacoes_sub2;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_escola' => 'int',
        'escola' => 'string',
        'cargo' => 'string',
        'funcao' => 'string',
        'data_aprovacao_mes1' => '?datetime',
        'data_aprovacao_mes2' => '?datetime',
        'data_aprovacao_mes3' => '?datetime',
        'data_aprovacao_mes4' => '?datetime',
        'data_aprovacao_mes5' => '?datetime',
        'data_aprovacao_mes6' => '?datetime',
        'data_aprovacao_mes7' => '?datetime',
        'data_aprovacao_sub1' => '?datetime',
        'data_aprovacao_sub2' => '?datetime',
        'data_impressao_mes1' => '?datetime',
        'data_impressao_mes2' => '?datetime',
        'data_impressao_mes3' => '?datetime',
        'data_impressao_mes4' => '?datetime',
        'data_impressao_mes5' => '?datetime',
        'data_impressao_mes6' => '?datetime',
        'data_impressao_mes7' => '?datetime',
        'data_impressao_sub1' => '?datetime',
        'data_impressao_sub2' => '?datetime',
        'observacoes_mes1' => '?string',
        'observacoes_mes2' => '?string',
        'observacoes_mes3' => '?string',
        'observacoes_mes4' => '?string',
        'observacoes_mes5' => '?string',
        'observacoes_mes6' => '?string',
        'observacoes_mes7' => '?string',
        'observacoes_sub1' => '?string',
        'observacoes_sub2' => '?string'
    ];

}
