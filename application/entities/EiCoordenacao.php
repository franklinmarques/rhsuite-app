<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiCoordenacao extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $depto;
    protected $area;
    protected $setor;
    protected $ano;
    protected $semestre;
    protected $is_coordenador;
    protected $is_supervisor;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'depto' => 'int',
        'area' => 'int',
        'setor' => 'int',
        'ano' => 'int',
        'semestre' => 'int',
        'is_coordenador' => '?int',
        'is_supervisor' => '?int'
    ];

}
