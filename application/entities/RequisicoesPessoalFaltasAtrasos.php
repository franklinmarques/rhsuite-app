<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RequisicoesPessoalFaltasAtrasos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_depto;
    protected $ano;
    protected $mes;
    protected $total_faltas;
    protected $total_atrasos;
    protected $tempo_total_atraso;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_depto' => 'int',
        'ano' => 'int',
        'mes' => 'int',
        'total_faltas' => 'int',
        'total_atrasos' => 'int',
        'tempo_total_atraso' => '?string'
    ];

}
