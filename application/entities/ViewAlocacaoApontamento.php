<?php

namespace App\Entities;

use CodeIgniter\Entity;

class ViewAlocacaoApontamento extends Entity
{
    protected $id;
    protected $id_alocado;
    protected $id_usuario;
    protected $data;
    protected $status;
    protected $qtde_dias;
    protected $dias_falta;
    protected $segundos_atraso;
    protected $dia_coberto;
    protected $posto_descoberto;
    protected $segundos_saldo;

    protected $casts = [
        'id' => 'int',
        'id_alocado' => 'int',
        'id_usuario' => 'int',
        'data' => 'datetime',
        'status' => 'string',
        'qtde_dias' => '?int',
        'dias_falta' => '?int',
        'segundos_atraso' => '?int',
        'dia_coberto' => '?int',
        'posto_descoberto' => '?int',
        'segundos_saldo' => '?int'
    ];

}
