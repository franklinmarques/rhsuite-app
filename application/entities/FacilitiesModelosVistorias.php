<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesModelosVistorias extends Entity
{
    protected $id;
    protected $id_modelo;
    protected $id_vistoria;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_modelo' => 'int',
        'id_vistoria' => 'int',
        'status' => '?int'
    ];

}
