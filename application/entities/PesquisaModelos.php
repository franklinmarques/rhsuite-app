<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PesquisaModelos extends Entity
{
    protected $id;
    protected $nome;
    protected $id_usuario_EMPRESA;
    protected $tipo;
    protected $observacoes;
    protected $instrucoes;
    protected $exclusao_bloqueada;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_usuario_EMPRESA' => 'int',
        'tipo' => 'string',
        'observacoes' => '?string',
        'instrucoes' => '?string',
        'exclusao_bloqueada' => 'int'
    ];

}
