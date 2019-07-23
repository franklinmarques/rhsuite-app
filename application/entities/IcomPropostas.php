<?php

include_once APPPATH . 'entities/Entity.php';

class IcomPropostas extends Entity
{
    protected $codigo;
    protected $id_cliente;
    protected $descricao;
    protected $data_entrega;
    protected $valor;
    protected $status;
    protected $custo_produto_servico;
    protected $custo_administrativo;
    protected $impostos;
    protected $margem_liquida;
    protected $detalhes;
    protected $arquivo;

    protected $casts = [
        'codigo' => 'int',
        'id_cliente' => 'int',
        'descricao' => 'string',
        'data_entrega' => 'date',
        'valor' => 'float',
        'status' => 'string',
        'custo_produto_servico' => '?float',
        'custo_administrativo' => '?float',
        'impostos' => '?float',
        'margem_liquida' => '?float',
        'detalhes' => '?string',
        'arquivo' => '?string'
    ];

}
