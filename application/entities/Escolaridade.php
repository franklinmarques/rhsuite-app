<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Escolaridade extends Entity
{
    protected $id;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string'
    ];

}