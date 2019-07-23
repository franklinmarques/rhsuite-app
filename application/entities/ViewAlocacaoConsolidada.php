<?php

namespace App\Entities;

use CodeIgniter\Entity;

class ViewAlocacaoConsolidada extends Entity
{
    protected $id;
    protected $data;
    protected $dias_ausentes;
    protected $qtde_dias;
    protected $dias_faltas;
    protected $segundos_atraso;
    protected $dia_coberto;
    protected $posto_descoberto;
    protected $segundos_saldo;
    protected $id_posto;

    protected $casts = [
        'id' => 'int',
        'data' => 'datetime',
        'dias_ausentes' => '?int',
        'qtde_dias' => '?float',
        'dias_faltas' => '?float',
        'segundos_atraso' => '?float',
        'dia_coberto' => '?float',
        'posto_descoberto' => '?float',
        'segundos_saldo' => '?float',
        'id_posto' => '?int'
    ];

}
