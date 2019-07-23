<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesFornecedoresPrestadores extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $tipo;
    protected $vinculo;
    protected $pessoa_contato;
    protected $telefone;
    protected $email;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'tipo' => 'int',
        'vinculo' => '?string',
        'pessoa_contato' => '?string',
        'telefone' => '?string',
        'email' => '?string',
        'status' => 'int'
    ];

}
