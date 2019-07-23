<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Pdi extends Entity
{
    protected $id;
    protected $nome;
    protected $descricao;
    protected $data_inicio;
    protected $data_termino;
    protected $observacao;
    protected $usuario;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'descricao' => '?string',
        'data_inicio' => '?datetime',
        'data_termino' => '?datetime',
        'observacao' => '?string',
        'usuario' => 'int',
        'status' => '?string'
    ];

}
