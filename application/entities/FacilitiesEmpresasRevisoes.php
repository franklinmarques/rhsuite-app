<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesEmpresasRevisoes extends Entity
{
    protected $id;
    protected $id_item;
    protected $nome;
    protected $tipo;

    protected $casts = [
        'id' => 'int',
        'id_item' => 'int',
        'nome' => 'string',
        'tipo' => 'string'
    ];

}
