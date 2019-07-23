<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresAlunos extends Entity
{
    protected $id;
    protected $id_escola;
    protected $nome;
    protected $hipotese_diagnostica;
    protected $endereco;
    protected $numero;
    protected $complemento;
    protected $municipio;
    protected $cep;
    protected $telefone;
    protected $contato;
    protected $email;
    protected $nome_responsavel;
    protected $periodo_manha;
    protected $periodo_tarde;
    protected $periodo_noite;

    protected $casts = [
        'id' => 'int',
        'id_escola' => 'int',
        'nome' => 'string',
        'hipotese_diagnostica' => '?string',
        'endereco' => '?string',
        'numero' => '?int',
        'complemento' => '?string',
        'municipio' => 'string',
        'cep' => '?string',
        'telefone' => '?string',
        'contato' => '?string',
        'email' => 'string',
        'nome_responsavel' => '?string',
        'periodo_manha' => 'int',
        'periodo_tarde' => 'int',
        'periodo_noite' => 'int'
    ];

}
