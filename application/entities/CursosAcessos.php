<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosAcessos extends Entity
{
    protected $id;
    protected $id_curso_usuario;
    protected $id_pagina;
    protected $data_acesso;
    protected $data_atualizacao;
    protected $tempo_estudo;
    protected $data_finalizacao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_curso_usuario' => 'int',
        'id_pagina' => 'int',
        'data_acesso' => 'datetime',
        'data_atualizacao' => '?datetime',
        'tempo_estudo' => '?string',
        'data_finalizacao' => '?datetime',
        'status' => 'int'
    ];

}
