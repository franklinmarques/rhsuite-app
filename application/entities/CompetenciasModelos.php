<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CompetenciasModelos extends Entity
{
    protected $id;
    protected $nome;
    protected $tipo;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'tipo' => 'string'
    ];

}
