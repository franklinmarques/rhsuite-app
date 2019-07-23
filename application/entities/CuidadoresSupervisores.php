<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresSupervisores extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $id_diretoria;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'id_diretoria' => 'int'
    ];

}
