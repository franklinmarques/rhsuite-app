<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EmpresaDepartamentos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string'
    ];

}
