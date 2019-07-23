<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdAlocados extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_vinculado;
    protected $cuidador;
    protected $escola;
    protected $municipio;
    protected $supervisor;
    protected $turno;
    protected $dia_inicial;
    protected $dia_limite;
    protected $remanejado;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_vinculado' => '?int',
        'cuidador' => '?string',
        'escola' => '?string',
        'municipio' => '?string',
        'supervisor' => '?string',
        'turno' => '?string',
        'dia_inicial' => '?int',
        'dia_limite' => '?int',
        'remanejado' => '?int'
    ];

}
