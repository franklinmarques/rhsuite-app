<?php

include_once APPPATH . 'entities/Entity.php';

class IcomProdutos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_setor;
    protected $codigo;
    protected $nome;
    protected $tipo;
    protected $preco;
    protected $tipo_cobranca;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_setor' => 'int',
        'codigo' => 'string',
        'nome' => 'string',
        'tipo' => 'string',
        'preco' => 'float',
        'tipo_cobranca' => 'string'
    ];

}
