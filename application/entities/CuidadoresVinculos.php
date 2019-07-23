<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresVinculos extends Entity
{
    protected $id;
    protected $id_supervisor;
    protected $id_unidade;

    protected $casts = [
        'id' => 'int',
        'id_supervisor' => 'int',
        'id_unidade' => 'int'
    ];

}
