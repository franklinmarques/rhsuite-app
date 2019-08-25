<?php

include_once APPPATH . 'entities/Entity.php';

class IcomAlocados extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_usuario;
    protected $nome_usuario;
    protected $banco_horas;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_usuario' => '?int',
        'nome_usuario' => 'string',
        'banco_horas' => '?string'
    ];

}
