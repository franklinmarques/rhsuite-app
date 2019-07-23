<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoexpResultado extends Entity
{
    protected $id;
    protected $id_avaliador;
    protected $id_pergunta;
    protected $id_alternativa;
    protected $resposta;
    protected $data_avaliacao;

    protected $casts = [
        'id' => 'int',
        'id_avaliador' => 'int',
        'id_pergunta' => 'int',
        'id_alternativa' => '?int',
        'resposta' => '?string',
        'data_avaliacao' => 'datetime'
    ];

}
