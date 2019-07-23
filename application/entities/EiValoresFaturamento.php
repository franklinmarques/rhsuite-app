<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiValoresFaturamento extends Entity
{
    protected $id;
    protected $id_contrato;
    protected $ano;
    protected $semestre;
    protected $id_cargo;
    protected $id_funcao;
    protected $valor;
    protected $valor_pagamento;

    protected $casts = [
        'id' => 'int',
        'id_contrato' => 'int',
        'ano' => 'int',
        'semestre' => 'int',
        'id_cargo' => '?int',
        'id_funcao' => 'int',
        'valor' => '?float',
        'valor_pagamento' => '?float'
    ];

}
