<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiMapaUnidades extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_escola;
    protected $escola;
    protected $municipio;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_escola' => '?int',
        'escola' => 'string',
        'municipio' => 'string'
    ];

}
