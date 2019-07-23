<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Cargos extends Entity
{
    protected $id;
    protected $cargo;
    protected $funcao;
    protected $id_usuario_EMPRESA;
    protected $peso_competencias_tecnicas;
    protected $peso_competencias_comportamentais;

    protected $casts = [
        'id' => 'int',
        'cargo' => 'string',
        'funcao' => 'string',
        'id_usuario_EMPRESA' => 'int',
        'peso_competencias_tecnicas' => 'int',
        'peso_competencias_comportamentais' => 'int'
    ];

}
