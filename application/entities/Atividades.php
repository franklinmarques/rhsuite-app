<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Atividades extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $tipo;
    protected $prioridade;
    protected $atividade;
    protected $data_cadastro;
    protected $data_limite;
    protected $data_lembrete;
    protected $data_fechamento;
    protected $status;
    protected $observacoes;
    protected $id_mae;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'tipo' => 'string',
        'prioridade' => 'int',
        'atividade' => 'string',
        'data_cadastro' => 'datetime',
        'data_limite' => 'datetime',
        'data_lembrete' => 'datetime',
        'data_fechamento' => '?datetime',
        'status' => 'int',
        'observacoes' => '?string',
        'id_mae' => '?int'
    ];

}
