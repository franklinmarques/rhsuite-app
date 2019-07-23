<?php

namespace App\Entities;

use CodeIgniter\Entity;

class UsuariosDemissao extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $id_empresa;
    protected $data_demissao;
    protected $motivo_demissao;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'id_empresa' => 'int',
        'data_demissao' => 'datetime',
        'motivo_demissao' => 'int',
        'observacoes' => '?string'
    ];

}
