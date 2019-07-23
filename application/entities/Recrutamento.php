<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Recrutamento extends Entity
{
    protected $id;
    protected $nome;
    protected $id_usuario_EMPRESA;
    protected $data_inicio;
    protected $data_termino;
    protected $requisitante;
    protected $tipo_vaga;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_usuario_EMPRESA' => 'int',
        'data_inicio' => 'datetime',
        'data_termino' => 'datetime',
        'requisitante' => 'string',
        'tipo_vaga' => '?string',
        'status' => '?string'
    ];

}
