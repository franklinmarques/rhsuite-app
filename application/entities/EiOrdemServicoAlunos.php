<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiOrdemServicoAlunos extends Entity
{
    protected $id;
    protected $id_ordem_servico_escola;
    protected $id_aluno;
    protected $id_aluno_curso;
    protected $data_inicio;
    protected $data_termino;
    protected $modulo;

    protected $casts = [
        'id' => 'int',
        'id_ordem_servico_escola' => 'int',
        'id_aluno' => 'int',
        'id_aluno_curso' => 'int',
        'data_inicio' => 'datetime',
        'data_termino' => 'datetime',
        'modulo' => 'string'
    ];

}
