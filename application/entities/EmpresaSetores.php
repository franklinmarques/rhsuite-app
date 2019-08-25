<?php

include_once APPPATH . 'entities/Entity.php';

class EmpresaSetores extends Entity
{
    protected $id;
    protected $id_area;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_area' => '?int',
        'nome' => 'string'
    ];

}
