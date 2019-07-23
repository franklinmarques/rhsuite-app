<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoStatus extends Entity
{
    protected $id;
    protected $competencia_tecnica;
    protected $competencia_comportamental;
    protected $id_avaliacao;

    protected $casts = [
        'id' => 'int',
        'competencia_tecnica' => 'int',
        'competencia_comportamental' => 'int',
        'id_avaliacao' => 'int'
    ];

}
