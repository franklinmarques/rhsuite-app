<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiInsumos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $tipo;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'tipo' => 'string'
    ];

}
