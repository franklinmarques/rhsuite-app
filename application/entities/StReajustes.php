<?php

include_once APPPATH . 'entities/Entity.php';

class StReajustes extends Entity
{
    protected $id;
    protected $id_cliente;
    protected $data_reajuste;
    protected $valor_indice;

    protected $casts = [
        'id' => 'int',
        'id_cliente' => 'int',
        'data_reajuste' => 'datetime',
        'valor_indice' => 'float'
    ];

}
