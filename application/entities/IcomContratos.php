<?php

include_once APPPATH . 'entities/Entity.php';

class IcomContratos extends Entity
{
    protected $codigo;
    protected $id_empresa;
    protected $codigo_proposta;
    protected $id_cliente;
    protected $centro_custo;
    protected $data_vencimento;
    protected $status_ativo;
    protected $arquivo;

    protected $casts = [
        'codigo' => 'int',
        'id_empresa' => 'int',
        'codigo_proposta' => 'int',
        'id_cliente' => 'int',
        'centro_custo' => '?string',
        'data_vencimento' => 'datetime',
        'status_ativo' => 'string',
        'arquivo' => '?string'
    ];

}
