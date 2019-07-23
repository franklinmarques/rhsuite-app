<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Estados extends Entity
{
    protected $cod_uf;
    protected $estado;
    protected $uf;

    protected $casts = [
        'cod_uf' => 'int',
        'estado' => 'string',
        'uf' => 'string'
    ];

}
