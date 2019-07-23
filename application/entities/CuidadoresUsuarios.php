<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CuidadoresUsuarios extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_usuario;
    protected $id_posto;
    protected $tipo_horario;
    protected $nivel;
    protected $data_atividade;
    protected $data_desligamento;
    protected $data_ferias;
    protected $data_retorno;
    protected $id_bck;
    protected $id_usuario_sub1;
    protected $data_atuacao1;
    protected $id_usuario_sub2;
    protected $data_atuacao2;
    protected $dias_acrescidos;
    protected $horas_acrescidas;
    protected $total_acrescido;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_usuario' => 'int',
        'id_posto' => '?int',
        'tipo_horario' => 'string',
        'nivel' => 'string',
        'data_atividade' => '?datetime',
        'data_desligamento' => '?datetime',
        'data_ferias' => '?datetime',
        'data_retorno' => '?datetime',
        'id_bck' => '?int',
        'id_usuario_sub1' => '?int',
        'data_atuacao1' => '?datetime',
        'id_usuario_sub2' => '?int',
        'data_atuacao2' => '?datetime',
        'dias_acrescidos' => '?float',
        'horas_acrescidas' => '?float',
        'total_acrescido' => '?float'
    ];

}
