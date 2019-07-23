<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StDetalhes extends Entity
{
    protected $id;
    protected $id_old;
    protected $codigo;
    protected $nome;
    protected $id_empresa;

    protected $casts = [
        'id' => 'int',
        'id_old' => 'int',
        'codigo' => 'string',
        'nome' => 'string',
        'id_empresa' => 'int'
    ];

}
