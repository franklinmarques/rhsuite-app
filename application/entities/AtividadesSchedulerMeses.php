<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AtividadesSchedulerMeses extends Entity
{
    protected $id_atividade_scheduler;
    protected $janeiro;
    protected $fevereiro;
    protected $marco;
    protected $abril;
    protected $maio;
    protected $junho;
    protected $julho;
    protected $agosto;
    protected $setembro;
    protected $outubro;
    protected $novembro;
    protected $dezembro;

    protected $casts = [
        'id_atividade_scheduler' => 'int',
        'janeiro' => '?int',
        'fevereiro' => '?int',
        'marco' => '?int',
        'abril' => '?int',
        'maio' => '?int',
        'junho' => '?int',
        'julho' => '?int',
        'agosto' => '?int',
        'setembro' => '?int',
        'outubro' => '?int',
        'novembro' => '?int',
        'dezembro' => '?int'
    ];

}
