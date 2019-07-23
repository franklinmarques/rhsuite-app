<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiAlocacao extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $depto;
    protected $id_diretoria;
    protected $diretoria;
    protected $id_supervisor;
    protected $supervisor;
    protected $municipio;
    protected $coordenador;
    protected $ano;
    protected $semestre;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'depto' => 'string',
        'id_diretoria' => 'int',
        'diretoria' => 'string',
        'id_supervisor' => 'int',
        'supervisor' => 'string',
        'municipio' => 'string',
        'coordenador' => 'string',
        'ano' => 'int',
        'semestre' => 'int'
    ];

}
