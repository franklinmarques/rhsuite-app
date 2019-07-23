<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosClientes extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $cliente;
    protected $email;
    protected $senha;
    protected $token;
    protected $url;
    protected $foto;
    protected $data_cadastro;
    protected $data_edicao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'cliente' => 'string',
        'email' => 'string',
        'senha' => 'string',
        'token' => 'string',
        'url' => '?string',
        'foto' => '?string',
        'data_cadastro' => 'datetime',
        'data_edicao' => '?datetime',
        'status' => 'int'
    ];

}
