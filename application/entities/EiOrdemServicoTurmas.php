<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiOrdemServicoTurmas extends Entity
{
    protected $id_os_aluno;
    protected $id_os_horario;

    protected $casts = [
        'id_os_aluno' => 'int',
        'id_os_horario' => 'int'
    ];

}
