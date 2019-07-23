<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RecrutamentoCandidatos extends Entity
{
    protected $id;
    protected $id_cargo;
    protected $id_usuario;

    protected $casts = [
        'id' => 'int',
        'id_cargo' => 'int',
        'id_usuario' => 'int'
    ];

}
