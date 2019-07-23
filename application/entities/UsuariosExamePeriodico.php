<?php

namespace App\Entities;

use CodeIgniter\Entity;

class UsuariosExamePeriodico extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $data_programada;
    protected $data_realizacao;
    protected $data_entrega;
    protected $data_entrega_copia;
    protected $local_exame;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'data_programada' => 'datetime',
        'data_realizacao' => '?datetime',
        'data_entrega' => '?datetime',
        'data_entrega_copia' => '?datetime',
        'local_exame' => '?string',
        'observacoes' => '?string'
    ];

}
