<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdCuidadores extends Entity
{
    protected $id;
    protected $id_cuidador;
    protected $id_escola;
    protected $id_supervisor;
    protected $turno;

    protected $casts = [
        'id' => 'int',
        'id_cuidador' => 'int',
        'id_escola' => 'int',
        'id_supervisor' => '?int',
        'turno' => 'string'
    ];

}
