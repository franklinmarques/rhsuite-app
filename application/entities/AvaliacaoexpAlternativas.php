<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoexpAlternativas extends Entity
{
    protected $id;
    protected $id_modelo;
    protected $id_pergunta;
    protected $alternativa;
    protected $peso;

    protected $casts = [
        'id' => 'int',
        'id_modelo' => 'int',
        'id_pergunta' => '?int',
        'alternativa' => 'string',
        'peso' => 'int'
    ];

}
