<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesContasItens extends Entity
{
    protected $id;
    protected $id_unidade;
    protected $nome;
    protected $medidor;
    protected $endereco;

    protected $casts = [
        'id' => 'int',
        'id_unidade' => 'int',
        'nome' => 'string',
        'medidor' => 'string',
        'endereco' => 'string'
    ];

}
