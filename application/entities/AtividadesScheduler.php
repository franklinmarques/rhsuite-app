<?php

namespace App\Entities;

use CodeIgniter\Entity;

class AtividadesScheduler extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_usuario;
    protected $atividade;
    protected $data_cadastro;
    protected $dia;
    protected $semana;
    protected $mes;
    protected $objetivos;
    protected $data_limite;
    protected $envolvidos;
    protected $observacoes;
    protected $processo_roteiro;
    protected $documento_1;
    protected $documento_2;
    protected $documento_3;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_usuario' => 'int',
        'atividade' => 'string',
        'data_cadastro' => 'datetime',
        'dia' => '?int',
        'semana' => '?int',
        'mes' => '?int',
        'objetivos' => '?string',
        'data_limite' => '?string',
        'envolvidos' => '?string',
        'observacoes' => '?string',
        'processo_roteiro' => '?string',
        'documento_1' => '?string',
        'documento_2' => '?string',
        'documento_3' => '?string'
    ];

}
