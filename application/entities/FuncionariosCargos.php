<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FuncionariosCargos extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $id_cargo;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'id_cargo' => 'int'
    ];

}
