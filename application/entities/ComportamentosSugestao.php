<?php

namespace App\Entities;

use CodeIgniter\Entity;

class ComportamentosSugestao extends Entity
{
    protected $id;
    protected $nome;
    protected $id_competencia_sugestao;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_competencia_sugestao' => 'int'
    ];

}
