<?php

namespace App\Entities;

use CodeIgniter\Entity;

class UsuariosContratos extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $data_assinatura;
    protected $depto;
    protected $area;
    protected $setor;
    protected $cargo;
    protected $funcao;
    protected $contrato;
    protected $valor_posto;
    protected $conversor_dia;
    protected $conversor_hora;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'data_assinatura' => 'datetime',
        'depto' => 'string',
        'area' => 'string',
        'setor' => 'string',
        'cargo' => 'string',
        'funcao' => 'string',
        'contrato' => '?string',
        'valor_posto' => 'float',
        'conversor_dia' => '?float',
        'conversor_hora' => '?float'
    ];

}
