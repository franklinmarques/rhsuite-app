<?php

namespace App\Entities;

use CodeIgniter\Entity;

class UsuariosIntegracao extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $data_inicio;
    protected $data_termino;
    protected $atividades_desenvolvidas;
    protected $realizadores;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'data_inicio' => 'datetime',
        'data_termino' => 'datetime',
        'atividades_desenvolvidas' => 'string',
        'realizadores' => 'string',
        'observacoes' => '?string'
    ];

}
