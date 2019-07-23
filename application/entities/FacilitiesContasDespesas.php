<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesContasDespesas extends Entity
{
    protected $id;
    protected $id_item;
    protected $nome;
    protected $valor;
    protected $data_vencimento;
    protected $mes;
    protected $ano;

    protected $casts = [
        'id' => 'int',
        'id_item' => 'int',
        'nome' => 'string',
        'valor' => 'float',
        'data_vencimento' => 'datetime',
        'mes' => 'int',
        'ano' => 'int'
    ];

}
