<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StAlocacao extends Entity
{
    protected $id;
    protected $id_old;
    protected $id_empresa;
    protected $depto;
    protected $area;
    protected $setor;
    protected $ano;
    protected $mes;
    protected $id_contrato;
    protected $contrato;
    protected $dia_fechamento;
    protected $mes_bloqueado;

    protected $casts = [
        'id' => 'int',
        'id_old' => 'int',
        'id_empresa' => 'int',
        'depto' => 'string',
        'area' => 'string',
        'setor' => 'string',
        'ano' => 'int',
        'mes' => 'int',
        'id_contrato' => '?int',
        'contrato' => '?string',
        'dia_fechamento' => 'int',
        'mes_bloqueado' => '?int'
    ];

}
