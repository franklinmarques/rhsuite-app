<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoexpPerguntas extends Entity
{
    protected $id;
    protected $id_modelo;
    protected $pergunta;
    protected $tipo;

    protected $casts = [
        'id' => 'int',
        'id_modelo' => 'int',
        'pergunta' => 'string',
        'tipo' => 'string'
    ];

}
