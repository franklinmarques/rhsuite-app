<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosPilulasAreas extends Entity
{
    protected $id;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string'
    ];

}
