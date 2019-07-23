<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresContratos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $depto;
    protected $diretoria;
    protected $id_supervisor;
    protected $municipio;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'depto' => 'string',
        'diretoria' => 'string',
        'id_supervisor' => '?int',
        'municipio' => 'string'
    ];

}
