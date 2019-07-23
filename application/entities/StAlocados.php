<?php

namespace App\Entities;

use CodeIgniter\Entity;

class StAlocados extends Entity
{
    protected $id;
    protected $id_old;
    protected $id_alocacao;
    protected $id_usuario;
    protected $nome;
    protected $cargo;
    protected $funcao;
    protected $id_posto;
    protected $valor_posto;
    protected $horario;
    protected $nivel;
    protected $tipo_bck;
    protected $data_afastamento;
    protected $data_retorno;
    protected $id_usuario_bck;
    protected $data_desligamento;
    protected $id_usuario_sub;
    protected $dias_acrescidos;
    protected $horas_acrescidas;
    protected $total_acrescido;
    protected $horas_saldo;
    protected $horas_saldo_acumulado;

    protected $casts = [
        'id' => 'int',
        'id_old' => 'int',
        'id_alocacao' => 'int',
        'id_usuario' => '?int',
        'nome' => 'string',
        'cargo' => 'string',
        'funcao' => 'string',
        'id_posto' => '?int',
        'valor_posto' => 'float',
        'horario' => 'string',
        'nivel' => 'string',
        'tipo_bck' => '?string',
        'data_afastamento' => '?datetime',
        'data_retorno' => '?datetime',
        'id_usuario_bck' => '?int',
        'data_desligamento' => '?datetime',
        'id_usuario_sub' => '?int',
        'dias_acrescidos' => '?float',
        'horas_acrescidas' => '?float',
        'total_acrescido' => '?float',
        'horas_saldo' => '?string',
        'horas_saldo_acumulado' => '?string'
    ];

}
