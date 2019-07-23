<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdApontamento extends Entity
{
    protected $id;
    protected $id_alocado;
    protected $data;
    protected $data_afastamento;
    protected $id_cuidador_sub;
    protected $status;
    protected $qtde_dias;
    protected $apontamento_asc;
    protected $apontamento_desc;
    protected $saldo;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_alocado' => 'int',
        'data' => 'datetime',
        'data_afastamento' => '?datetime',
        'id_cuidador_sub' => '?int',
        'status' => 'string',
        'qtde_dias' => '?int',
        'apontamento_asc' => '?string',
        'apontamento_desc' => '?string',
        'saldo' => '?int',
        'observacoes' => '?string'
    ];

}
