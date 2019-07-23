<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiContratos extends Entity
{
    protected $id;
    protected $id_cliente;
    protected $contrato;
    protected $data_inicio;
    protected $data_termino;
    protected $data_reajuste1;
    protected $indice_reajuste1;
    protected $data_reajuste2;
    protected $indice_reajuste2;
    protected $data_reajuste3;
    protected $indice_reajuste3;
    protected $data_reajuste4;
    protected $indice_reajuste4;
    protected $data_reajuste5;
    protected $indice_reajuste5;

    protected $casts = [
        'id' => 'int',
        'id_cliente' => 'int',
        'contrato' => 'string',
        'data_inicio' => 'datetime',
        'data_termino' => 'datetime',
        'data_reajuste1' => '?datetime',
        'indice_reajuste1' => '?float',
        'data_reajuste2' => '?datetime',
        'indice_reajuste2' => '?float',
        'data_reajuste3' => '?datetime',
        'indice_reajuste3' => '?float',
        'data_reajuste4' => '?datetime',
        'indice_reajuste4' => '?float',
        'data_reajuste5' => '?datetime',
        'indice_reajuste5' => '?float'
    ];

}
