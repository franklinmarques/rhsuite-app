<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AlocacaoServicos extends Entity
{
    protected $id;
    protected $id_contrato;
    protected $tipo;
    protected $descricao;
    protected $data_reajuste;
    protected $valor;

    protected $casts = [
        'id' => 'int',
        'id_contrato' => 'int',
        'tipo' => 'int',
        'descricao' => 'string',
        'data_reajuste' => '?datetime',
        'valor' => 'float'
    ];

}
