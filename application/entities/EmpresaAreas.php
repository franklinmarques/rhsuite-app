<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EmpresaAreas extends Entity
{
    protected $id;
    protected $id_departamento;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_departamento' => '?int',
        'nome' => 'string'
    ];

}
