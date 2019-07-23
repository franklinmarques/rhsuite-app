<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresAlocacao extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $data;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'data' => 'datetime',
        'status' => 'int'
    ];

}
