<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EmpresaCargos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $familia_CBO;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'familia_CBO' => '?int'
    ];

}
