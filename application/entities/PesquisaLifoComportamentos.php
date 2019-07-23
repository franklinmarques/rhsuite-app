<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PesquisaLifoComportamentos extends Entity
{
    protected $id;
    protected $id_estilo;
    protected $situacao_comportamental;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_estilo' => 'int',
        'situacao_comportamental' => 'string',
        'nome' => 'string'
    ];

}
