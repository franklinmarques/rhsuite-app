<?php

include_once APPPATH . 'entities/Entity.php';

class StDetalhesEventos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $codigo;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'codigo' => 'string',
        'nome' => 'string'
    ];

}
