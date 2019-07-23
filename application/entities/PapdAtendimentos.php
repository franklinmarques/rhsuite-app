<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PapdAtendimentos extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $id_paciente;
    protected $id_atividade;
    protected $data_atendimento;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'id_paciente' => 'int',
        'id_atividade' => 'int',
        'data_atendimento' => 'datetime'
    ];

}
