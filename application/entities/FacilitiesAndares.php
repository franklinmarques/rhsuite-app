<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesAndares extends Entity
{
    protected $id;
    protected $id_unidade;
    protected $andar;

    protected $casts = [
        'id' => 'int',
        'id_unidade' => 'int',
        'andar' => 'string'
    ];

}
