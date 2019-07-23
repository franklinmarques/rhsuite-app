<?php

namespace App\Entities;

use CodeIgniter\Entity;

class GestaoPessoalExperiencia extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_depto;
    protected $ano;
    protected $mes;
    protected $total_avaliados;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_depto' => 'int',
        'ano' => 'int',
        'mes' => 'int',
        'total_avaliados' => 'int'
    ];

}
