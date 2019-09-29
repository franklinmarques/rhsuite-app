<?php

include_once APPPATH . 'entities/Entity.php';

class EmtuAlocacao extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_depto;
    protected $id_area;
    protected $id_setor;
    protected $mes;
    protected $ano;
    protected $dia_fechamento;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_depto' => 'int',
        'id_area' => 'int',
        'id_setor' => 'int',
        'mes' => 'int',
        'ano' => 'int',
        'dia_fechamento' => '?int'
    ];

}
