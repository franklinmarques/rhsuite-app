<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosAreas extends Entity
{
    protected $id;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string'
    ];

}
