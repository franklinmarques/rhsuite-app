<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdMatriculados extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_aluno;
    protected $aluno;
    protected $escola;
    protected $supervisor;
    protected $hipotese_diagnostica;
    protected $turno;
    protected $status;
    protected $dia_inicial;
    protected $dia_limite;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_aluno' => '?int',
        'aluno' => 'string',
        'escola' => 'string',
        'supervisor' => 'string',
        'hipotese_diagnostica' => 'string',
        'turno' => 'string',
        'status' => 'string',
        'dia_inicial' => '?int',
        'dia_limite' => '?int'
    ];

}
