<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Avaliacao extends Entity
{
    protected $id;
    protected $nome;
    protected $data;
    protected $id_usuario_EMPRESA;
    protected $data_fim;
    protected $status;
    protected $descricao;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'data' => 'datetime',
        'id_usuario_EMPRESA' => 'int',
        'data_fim' => 'datetime',
        'status' => 'int',
        'descricao' => 'string'
    ];

}
