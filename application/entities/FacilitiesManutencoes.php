<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesManutencoes extends Entity
{
    protected $id;
    protected $id_item;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_item' => 'int',
        'nome' => 'string'
    ];

}
