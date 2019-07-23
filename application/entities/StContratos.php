<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StContratos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $depto;
    protected $area;
    protected $contrato;
    protected $id_usuario_gestor;
    protected $data_assinatura;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'depto' => 'string',
        'area' => 'string',
        'contrato' => 'string',
        'id_usuario_gestor' => '?int',
        'data_assinatura' => '?datetime'
    ];

}
