<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresEscolas extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $endereco;
    protected $numero;
    protected $complemento;
    protected $cep;
    protected $telefone;
    protected $contato;
    protected $email;
    protected $id_cliente;
    protected $periodo_manha;
    protected $periodo_tarde;
    protected $periodo_noite;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'endereco' => '?string',
        'numero' => '?int',
        'complemento' => '?string',
        'cep' => '?string',
        'telefone' => '?string',
        'contato' => '?string',
        'email' => 'string',
        'id_cliente' => '?int',
        'periodo_manha' => 'int',
        'periodo_tarde' => 'int',
        'periodo_noite' => 'int'
    ];

}
