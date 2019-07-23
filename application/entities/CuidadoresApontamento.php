<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresApontamento extends Entity
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
    protected $hora_glosa;
    protected $hora_saida_antecipada;
    protected $detalhes;
    protected $observacoes;
    protected $status;
    protected $id_alocado_bck;
    protected $id_alocado_bck2;

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
        'hora_glosa' => '?string',
        'hora_saida_antecipada' => '?string',
        'detalhes' => '?int',
        'observacoes' => '?string',
        'status' => 'string',
        'id_alocado_bck' => '?int',
        'id_alocado_bck2' => '?int'
    ];

}
