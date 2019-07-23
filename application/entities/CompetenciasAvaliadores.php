<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CompetenciasAvaliadores extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $id_avaliado;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'id_avaliado' => 'int'
    ];

}
