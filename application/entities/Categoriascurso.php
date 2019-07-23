<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Categoriascurso extends Entity
{
    protected $Id;
    protected $categoria;

    protected $casts = [
        'Id' => 'int',
        'categoria' => '?string'
    ];

}
