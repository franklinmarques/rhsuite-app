<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AlocacaoReajuste extends Entity
{
    protected $id;
    protected $id_cliente;
    protected $data_reajuste;
    protected $valor_indice;

    protected $casts = [
        'id' => 'int',
        'id_cliente' => 'int',
        'data_reajuste' => 'datetime',
        'valor_indice' => 'float'
    ];

}
