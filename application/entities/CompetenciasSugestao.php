<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CompetenciasSugestao extends Entity
{
    protected $id;
    protected $nome;
    protected $tipo;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'tipo' => 'int'
    ];

}
