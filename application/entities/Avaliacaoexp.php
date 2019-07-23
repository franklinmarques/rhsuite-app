<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Avaliacaoexp extends Entity
{
    protected $id;
    protected $nome;
    protected $id_modelo;
    protected $data_inicio;
    protected $data_termino;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_modelo' => 'int',
        'data_inicio' => 'datetime',
        'data_termino' => 'datetime'
    ];

}
