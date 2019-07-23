<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiMatriculados extends Entity
{
    protected $id;
    protected $id_alocacao_escola;
    protected $id_os_aluno;
    protected $id_aluno;
    protected $aluno;
    protected $id_aluno_curso;
    protected $id_curso;
    protected $curso;
    protected $modulo;
    protected $hipotese_diagnostica;
    protected $status;
    protected $data_inicio;
    protected $data_termino;
    protected $data_recesso;

    protected $casts = [
        'id' => 'int',
        'id_alocacao_escola' => 'int',
        'id_os_aluno' => '?int',
        'id_aluno' => '?int',
        'aluno' => 'string',
        'id_aluno_curso' => 'int',
        'id_curso' => 'int',
        'curso' => 'string',
        'modulo' => '?string',
        'hipotese_diagnostica' => '?string',
        'status' => 'string',
        'data_inicio' => '?datetime',
        'data_termino' => '?datetime',
        'data_recesso' => '?datetime'
    ];

}
