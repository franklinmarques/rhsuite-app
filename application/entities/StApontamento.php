<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StApontamento extends Entity
{
    protected $id;
    protected $id_old;
    protected $id_alocado;
    protected $data;
    protected $status;
    protected $desconto_folha;
    protected $apontamento_asc;
    protected $apontamento_desc;
    protected $apontamento_saldo;
    protected $glosa_dias;
    protected $glosa_horas;
    protected $horario_entrada;
    protected $horario_intervalo;
    protected $horario_retorno;
    protected $horario_saida;
    protected $id_alocado_bck;
    protected $id_detalhe;
    protected $codigo_detalhe;
    protected $descricao_detalhe;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_old' => 'int',
        'id_alocado' => 'int',
        'data' => 'datetime',
        'status' => 'string',
        'desconto_folha' => '?string',
        'apontamento_asc' => '?string',
        'apontamento_desc' => '?string',
        'apontamento_saldo' => '?string',
        'glosa_dias' => '?int',
        'glosa_horas' => '?string',
        'horario_entrada' => '?datetime',
        'horario_intervalo' => '?datetime',
        'horario_retorno' => '?datetime',
        'horario_saida' => '?datetime',
        'id_alocado_bck' => '?int',
        'id_detalhe' => '?int',
        'codigo_detalhe' => '?string',
        'descricao_detalhe' => '?string',
        'observacoes' => '?string'
    ];

}
