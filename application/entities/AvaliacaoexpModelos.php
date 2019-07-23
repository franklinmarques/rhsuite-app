<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AvaliacaoexpModelos extends Entity
{
    protected $id;
    protected $nome;
    protected $id_usuario_EMPRESA;
    protected $tipo;
    protected $observacao;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_usuario_EMPRESA' => 'int',
        'tipo' => 'string',
        'observacao' => '?string'
    ];

}
