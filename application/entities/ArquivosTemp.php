<?php

namespace App\Entities;

use CodeIgniter\Entity;

class ArquivosTemp extends Entity
{
    protected $id;
    protected $arquivo;
    protected $usuario;

    protected $casts = [
        'id' => 'int',
        'arquivo' => '?string',
        'usuario' => '?int'
    ];

}
