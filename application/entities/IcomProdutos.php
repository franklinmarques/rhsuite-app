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
    protected $custo;
    protected $tipo_cobranca;
    protected $centro_custo;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_setor' => 'int',
        'codigo' => 'string',
        'nome' => 'string',
        'tipo' => 'string',
        'preco' => 'float',
        'custo' => '?float',
        'tipo_cobranca' => 'string',
        'centro_custo' => '?string'
    ];

}
