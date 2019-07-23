<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RecrutamentoCargos extends Entity
{
    protected $id;
    protected $id_recrutamento;
    protected $cargo;

    protected $casts = [
        'id' => 'int',
        'id_recrutamento' => 'int',
        'cargo' => 'string'
    ];

}
