<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiAlocacaoEscolas extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_os_escola;
    protected $id_escola;
    protected $codigo;
    protected $escola;
    protected $municipio;
    protected $ordem_servico;
    protected $contrato;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_os_escola' => '?int',
        'id_escola' => '?int',
        'codigo' => '?int',
        'escola' => 'string',
        'municipio' => 'string',
        'ordem_servico' => 'string',
        'contrato' => 'string'
    ];

}
