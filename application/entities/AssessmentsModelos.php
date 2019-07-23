<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AssessmentsModelos extends Entity
{
    protected $id;
    protected $nome;
    protected $id_empresa;
    protected $tipo;
    protected $tipo_old;
    protected $observacoes;
    protected $instrucoes;
    protected $aleatorizacao;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_empresa' => 'int',
        'tipo' => 'string',
        'tipo_old' => 'string',
        'observacoes' => '?string',
        'instrucoes' => '?string',
        'aleatorizacao' => '?string'
    ];

}
