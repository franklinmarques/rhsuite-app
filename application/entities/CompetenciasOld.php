<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CompetenciasOld extends Entity
{
    protected $id;
    protected $nome;
    protected $id_cargo;
    protected $peso;
    protected $tipo_competencia;
    protected $id_sugestao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_cargo' => 'int',
        'peso' => 'string',
        'tipo_competencia' => 'int',
        'id_sugestao' => 'int',
        'status' => 'int'
    ];

}
