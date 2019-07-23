<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiOrdemServico extends Entity
{
    protected $id;
    protected $id_contrato;
    protected $nome;
    protected $numero_empenho;
    protected $ano;
    protected $semestre;

    protected $casts = [
        'id' => 'int',
        'id_contrato' => 'int',
        'nome' => 'string',
        'numero_empenho' => '?string',
        'ano' => 'int',
        'semestre' => 'int'
    ];

}
