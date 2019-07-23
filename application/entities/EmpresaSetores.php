<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EmpresaSetores extends Entity
{
    protected $id;
    protected $id_area;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_area' => '?int',
        'nome' => 'string'
    ];

}
