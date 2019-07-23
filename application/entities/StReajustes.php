<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StReajustes extends Entity
{
    protected $id;
    protected $id_contrato;
    protected $data_reajuste;
    protected $valor_indice;

    protected $casts = [
        'id' => 'int',
        'id_contrato' => 'int',
        'data_reajuste' => 'datetime',
        'valor_indice' => 'float'
    ];

}
