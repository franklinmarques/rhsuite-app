<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CdDiretorias extends Entity
{
    protected $id;
    protected $nome;
    protected $alias;
    protected $id_empresa;
    protected $depto;
    protected $municipio;
    protected $contrato;
    protected $id_coordenador;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'alias' => '?string',
        'id_empresa' => 'int',
        'depto' => 'string',
        'municipio' => 'string',
        'contrato' => 'string',
        'id_coordenador' => '?int'
    ];

}
