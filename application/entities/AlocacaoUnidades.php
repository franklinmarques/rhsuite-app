<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AlocacaoUnidades extends Entity
{
    protected $id;
    protected $id_contrato;
    protected $setor;

    protected $casts = [
        'id' => 'int',
        'id_contrato' => 'int',
        'setor' => 'string'
    ];

}
