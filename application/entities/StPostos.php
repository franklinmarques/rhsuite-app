<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StPostos extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $ano;
    protected $mes;
    protected $depto;
    protected $area;
    protected $setor;
    protected $cargo;
    protected $funcao;
    protected $total_dias_mensais;
    protected $total_horas_diarias;
    protected $valor_dia;
    protected $valor_hora;
    protected $horario_entrada;
    protected $horario_saida;
    protected $matricula;
    protected $login;
    protected $contrato;
    protected $tipo_vinculo;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'ano' => 'int',
        'mes' => 'int',
        'depto' => 'string',
        'area' => 'string',
        'setor' => 'string',
        'cargo' => 'string',
        'funcao' => 'string',
        'total_dias_mensais' => 'int',
        'total_horas_diarias' => 'int',
        'valor_dia' => 'float',
        'valor_hora' => 'float',
        'horario_entrada' => '?string',
        'horario_saida' => '?string',
        'matricula' => '?string',
        'login' => '?string',
        'contrato' => '?string',
        'tipo_vinculo' => '?int'
    ];

}
