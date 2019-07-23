<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoexpAvaliadores extends Entity
{
    protected $id;
    protected $id_avaliado;
    protected $id_avaliador;
    protected $data_avaliacao;
    protected $id_evento;

    protected $casts = [
        'id' => 'int',
        'id_avaliado' => 'int',
        'id_avaliador' => 'int',
        'data_avaliacao' => 'datetime',
        'id_evento' => '?int'
    ];

}
