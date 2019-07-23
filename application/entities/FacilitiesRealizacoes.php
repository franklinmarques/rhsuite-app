<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesRealizacoes extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_modelo;
    protected $mes;
    protected $ano;
    protected $pendencias;
    protected $id_usuario_vistoriador;
    protected $tipo_executor;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_modelo' => 'int',
        'mes' => 'int',
        'ano' => 'int',
        'pendencias' => 'int',
        'id_usuario_vistoriador' => '?int',
        'tipo_executor' => '?string',
        'status' => 'string'
    ];

}
