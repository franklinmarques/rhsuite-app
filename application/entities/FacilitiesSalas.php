<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesSalas extends Entity
{
    protected $id;
    protected $id_andar;
    protected $sala;

    protected $casts = [
        'id' => 'int',
        'id_andar' => 'int',
        'sala' => 'string'
    ];

}
