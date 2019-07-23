<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdFrequencias extends Entity
{
    protected $id;
    protected $id_matriculado;
    protected $data;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_matriculado' => 'int',
        'data' => 'datetime',
        'status' => '?string'
    ];

}
