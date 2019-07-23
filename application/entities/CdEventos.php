<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdEventos extends Entity
{
    protected $id;
    protected $codigo;
    protected $nome;
    protected $id_empresa;

    protected $casts = [
        'id' => 'int',
        'codigo' => 'string',
        'nome' => 'string',
        'id_empresa' => 'int'
    ];

}
