<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoResultado extends Entity
{
    protected $id;
    protected $id_avaliacao;
    protected $atitude;
    protected $nivel;
    protected $avaliador;
    protected $avaliado;
    protected $id_dimensao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_avaliacao' => 'int',
        'atitude' => 'int',
        'nivel' => 'int',
        'avaliador' => 'int',
        'avaliado' => 'int',
        'id_dimensao' => 'int',
        'status' => 'int'
    ];

}
