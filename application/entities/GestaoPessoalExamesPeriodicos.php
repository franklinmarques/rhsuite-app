<?php

namespace App\Entities;

use CodeIgniter\Entity;

class GestaoPessoalExamesPeriodicos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_depto;
    protected $ano;
    protected $mes;
    protected $total_colaboradores;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_depto' => 'int',
        'ano' => 'int',
        'mes' => 'int',
        'total_colaboradores' => 'int'
    ];

}
