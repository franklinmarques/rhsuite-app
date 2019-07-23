<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CargosCompetencias extends Entity
{
    protected $id;
    protected $nome;
    protected $id_cargo;
    protected $tipo_competencia;
    protected $peso;
    protected $id_modelo;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_cargo' => 'int',
        'tipo_competencia' => 'string',
        'peso' => 'int',
        'id_modelo' => '?int'
    ];

}
