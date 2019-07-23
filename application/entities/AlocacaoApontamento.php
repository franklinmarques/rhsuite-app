<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AlocacaoApontamento extends Entity
{
    protected $id;
    protected $id_alocado;
    protected $data;
    protected $hora_entrada;
    protected $hora_intervalo;
    protected $hora_retorno;
    protected $hora_saida;
    protected $qtde_dias;
    protected $hora_atraso;
    protected $apontamento_extra;
    protected $apontamento_desc;
    protected $apontamento_saldo;
    protected $apontamento_saldo_old;
    protected $hora_glosa;
    protected $detalhes;
    protected $observacoes;
    protected $status;
    protected $id_alocado_bck;

    protected $casts = [
        'id' => 'int',
        'id_alocado' => 'int',
        'data' => 'datetime',
        'hora_entrada' => '?datetime',
        'hora_intervalo' => '?datetime',
        'hora_retorno' => '?datetime',
        'hora_saida' => '?datetime',
        'qtde_dias' => '?int',
        'hora_atraso' => '?string',
        'apontamento_extra' => '?string',
        'apontamento_desc' => '?string',
        'apontamento_saldo' => '?string',
        'apontamento_saldo_old' => '?string',
        'hora_glosa' => '?string',
        'detalhes' => '?int',
        'observacoes' => '?string',
        'status' => 'string',
        'id_alocado_bck' => '?int'
    ];

}
