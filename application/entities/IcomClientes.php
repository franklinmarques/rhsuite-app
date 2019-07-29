<?php

include_once APPPATH . 'entities/Entity.php';

class IcomClientes extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_setor;
    protected $nome;
    protected $contato_principal;
    protected $telefone_principal;
    protected $email_principal;
    protected $contato_secundario;
    protected $telefone_secundario;
    protected $email_secundario;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_setor' => 'int',
        'nome' => 'string',
        'contrato_principal' => '?string',
        'telefone_principal' => '?string',
        'email_principal' => '?string',
        'contrato_secundario' => '?string',
        'telefone_secundario' => '?string',
        'email_secundario' => '?string'
    ];

}
