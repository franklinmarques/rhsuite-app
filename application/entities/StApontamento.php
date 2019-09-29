<?php

include_once APPPATH . 'entities/Entity.php';

class StApontamento extends Entity
{
    protected $id;
    protected $id_alocado;
    protected $data;
    protected $horario_entrada;
    protected $horario_intervalo;
    protected $horario_retorno;
    protected $horario_saida;
    protected $qtde_dias;
    protected $hora_atraso;
    protected $apontamento_asc;
    protected $apontamento_desc;
    protected $apontamento_saldo;
    protected $hora_glosa;
    protected $detalhes;
    protected $observacoes;
    protected $status;
    protected $id_alocado_bck;

    protected $casts = [
        'id' => 'int',
        'id_alocado' => 'int',
        'data' => 'datetime',
        'horario_entrada' => '?datetime',
        'horario_intervalo' => '?datetime',
        'horario_retorno' => '?datetime',
        'horario_saida' => '?datetime',
        'qtde_dias' => '?int',
        'hora_atraso' => '?string',
        'apontamento_extra' => '?string',
        'apontamento_desc' => '?string',
        'apontamento_saldo' => '?string',
        'hora_glosa' => '?string',
        'detalhes' => '?int',
        'observacoes' => '?string',
        'status' => 'string',
        'id_alocado_bck' => '?int',
    ];

}
