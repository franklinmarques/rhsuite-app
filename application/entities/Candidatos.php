<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Candidatos extends Entity
{
    protected $id;
    protected $empresa;
    protected $nome;
    protected $data_nascimento;
    protected $sexo;
    protected $estado_civil;
    protected $nome_mae;
    protected $nome_pai;
    protected $cpf;
    protected $rg;
    protected $pis;
    protected $logradouro;
    protected $numero;
    protected $complemento;
    protected $bairro;
    protected $cidade;
    protected $estado;
    protected $cep;
    protected $escolaridade;
    protected $deficiencia;
    protected $foto;
    protected $telefone;
    protected $email;
    protected $senha;
    protected $token;
    protected $data_inscricao;
    protected $fonte_contratacao;
    protected $data_edicao;
    protected $nivel_acesso;
    protected $url;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'empresa' => 'int',
        'nome' => 'string',
        'data_nascimento' => '?datetime',
        'sexo' => '?string',
        'estado_civil' => '?int',
        'nome_mae' => '?string',
        'nome_pai' => '?string',
        'cpf' => '?string',
        'rg' => '?string',
        'pis' => '?string',
        'logradouro' => '?string',
        'numero' => '?int',
        'complemento' => '?string',
        'bairro' => '?string',
        'cidade' => '?int',
        'estado' => '?int',
        'cep' => '?string',
        'escolaridade' => '?int',
        'deficiencia' => '?int',
        'foto' => '?string',
        'telefone' => 'string',
        'email' => 'string',
        'senha' => 'string',
        'token' => 'string',
        'data_inscricao' => '?datetime',
        'fonte_contratacao' => '?string',
        'data_edicao' => '?datetime',
        'nivel_acesso' => 'string',
        'url' => '?string',
        'status' => 'string'
    ];

}
