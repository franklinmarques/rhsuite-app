<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiFuncoesSupervisionadas extends Entity
{
    protected $id;
    protected $id_supervisor;
    protected $cargo;
    protected $funcao;

    protected $casts = [
        'id' => 'int',
        'id_supervisor' => 'int',
        'cargo' => 'int',
        'funcao' => 'int'
    ];

}
