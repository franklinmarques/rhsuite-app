<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdSupervisores extends Entity
{
    protected $id;
    protected $id_supervisor;
    protected $id_escola;
    protected $turno;

    protected $casts = [
        'id' => 'int',
        'id_supervisor' => 'int',
        'id_escola' => 'int',
        'turno' => 'string'
    ];

}
