<?php

include_once APPPATH . 'entities/Entity.php';

class StUnidades extends Entity
{
    protected $id;
    protected $id_contrato;
    protected $setor;

    protected $casts = [
        'id' => 'int',
        'id_contrato' => 'int',
        'setor' => 'string'
    ];

}
