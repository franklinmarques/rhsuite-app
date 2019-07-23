<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RequisicoesPessoalAprovadores extends Entity
{
    protected $id_usuario;

    protected $casts = [
        'id_usuario' => 'int'
    ];

}
