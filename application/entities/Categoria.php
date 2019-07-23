<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Categoria extends Entity
{
    protected $id;
    protected $curso;

    protected $casts = [
        'id' => 'int',
        'curso' => 'string'
    ];

}
