<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Deficiencias extends Entity
{
    protected $id;
    protected $tipo;

    protected $casts = [
        'id' => 'int',
        'tipo' => 'string'
    ];

}
