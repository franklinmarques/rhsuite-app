<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StServicos extends Entity
{
    protected $id;
    protected $id_contrato;
    protected $tipo;
    protected $descricao;
    protected $valor;

    protected $casts = [
        'id' => 'int',
        'id_contrato' => 'int',
        'tipo' => 'int',
        'descricao' => 'string',
        'valor' => 'float'
    ];

}
