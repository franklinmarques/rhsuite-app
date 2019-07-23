<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CompetenciasAvaliados extends Entity
{
    protected $id;
    protected $id_competencia;
    protected $id_usuario;

    protected $casts = [
        'id' => 'int',
        'id_competencia' => 'int',
        'id_usuario' => 'int'
    ];

}
