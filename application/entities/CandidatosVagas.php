<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CandidatosVagas extends Entity
{
    protected $id;
    protected $id_candidato;
    protected $codigo_vaga;
    protected $data_cadastro;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_candidato' => 'int',
        'codigo_vaga' => 'int',
        'data_cadastro' => 'datetime',
        'status' => '?string'
    ];

}
