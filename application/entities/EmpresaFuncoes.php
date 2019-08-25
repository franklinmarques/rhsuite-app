<?php

include_once APPPATH . 'entities/Entity.php';

class EmpresaFuncoes extends Entity
{
    protected $id;
    protected $id_cargo;
    protected $nome;
    protected $ocupacao_CBO;

    protected $casts = [
        'id' => 'int',
        'id_cargo' => 'int',
        'nome' => 'string',
        'ocupacao_CBO' => '?int'
    ];

}
