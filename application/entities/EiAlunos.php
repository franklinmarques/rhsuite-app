<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiAlunos extends Entity
{
    protected $id;
    protected $nome;
    protected $id_escola;
    protected $endereco;
    protected $numero;
    protected $complemento;
    protected $municipio;
    protected $telefone;
    protected $contato;
    protected $email;
    protected $cep;
    protected $hipotese_diagnostica;
    protected $nome_responsavel;
    protected $observacoes;
    protected $data_matricula;
    protected $data_afastamento;
    protected $data_desligamento;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'id_escola' => '?int',
        'endereco' => '?string',
        'numero' => '?int',
        'complemento' => '?string',
        'municipio' => '?string',
        'telefone' => '?string',
        'contato' => '?string',
        'email' => '?string',
        'cep' => '?string',
        'hipotese_diagnostica' => '?string',
        'nome_responsavel' => '?string',
        'observacoes' => '?string',
        'data_matricula' => '?datetime',
        'data_afastamento' => '?datetime',
        'data_desligamento' => '?datetime',
        'status' => 'string'
    ];

}
