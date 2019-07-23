<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PesquisaCategorias extends Entity
{
    protected $id;
    protected $id_modelo;
    protected $categoria;

    protected $casts = [
        'id' => 'int',
        'id_modelo' => 'int',
        'categoria' => 'string'
    ];

}
