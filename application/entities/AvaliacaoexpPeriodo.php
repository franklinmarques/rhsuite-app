<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoexpPeriodo extends Entity
{
    protected $id_avaliado;
    protected $pontos_fortes;
    protected $pontos_fracos;
    protected $feedback1;
    protected $data_feedback1;
    protected $feedback2;
    protected $data_feedback2;
    protected $feedback3;
    protected $data_feedback3;
    protected $parecer_final;
    protected $data;

    protected $casts = [
        'id_avaliado' => 'int',
        'pontos_fortes' => '?string',
        'pontos_fracos' => '?string',
        'feedback1' => '?string',
        'data_feedback1' => '?datetime',
        'feedback2' => '?string',
        'data_feedback2' => '?datetime',
        'feedback3' => '?string',
        'data_feedback3' => '?datetime',
        'parecer_final' => '?string',
        'data' => '?datetime'
    ];

}
