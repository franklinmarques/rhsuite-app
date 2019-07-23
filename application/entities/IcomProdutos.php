<?php

include_once APPPATH . 'entities/Entity.php';

class IcomProdutos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $codigo;
    protected $nome;
    protected $tipo;
    protected $preco;
    protected $tipo_preco;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'codigo' => 'string',
        'nome' => 'string',
        'tipo' => 'string',
        'preco' => 'float',
        'tipo_preco' => 'string'
    ];

}
