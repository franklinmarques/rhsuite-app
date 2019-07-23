<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AlocacaoContratos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_usuario;
    protected $nome;
    protected $depto;
    protected $area;
    protected $contrato;
    protected $data_assinatura;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_usuario' => '?int',
        'nome' => 'string',
        'depto' => 'string',
        'area' => 'string',
        'contrato' => 'string',
        'data_assinatura' => '?datetime'
    ];

}
