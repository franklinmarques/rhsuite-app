<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliadorAvaliados extends Entity
{
    protected $id;
    protected $avaliado;
    protected $avaliador_1;
    protected $avaliador_2;
    protected $avaliador_3;
    protected $avaliador_4;
    protected $avaliador_5;
    protected $id_avaliacao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'avaliado' => 'int',
        'avaliador_1' => 'int',
        'avaliador_2' => 'int',
        'avaliador_3' => 'int',
        'avaliador_4' => 'int',
        'avaliador_5' => 'int',
        'id_avaliacao' => 'int',
        'status' => 'int'
    ];

}
