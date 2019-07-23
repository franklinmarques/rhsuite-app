<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiCursos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_diretoria;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_diretoria' => 'int',
        'nome' => 'string'
    ];

}
