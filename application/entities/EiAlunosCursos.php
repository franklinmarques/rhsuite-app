<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiAlunosCursos extends Entity
{
    protected $id;
    protected $id_aluno;
    protected $id_curso;
    protected $id_escola;
    protected $qtde_semestre;
    protected $semestre_inicial;
    protected $semestre_final;
    protected $status_ativo;

    protected $casts = [
        'id' => 'int',
        'id_aluno' => 'int',
        'id_curso' => 'int',
        'id_escola' => 'int',
        'qtde_semestre' => 'int',
        'semestre_inicial' => 'string',
        'semestre_final' => '?string',
        'status_ativo' => '?int'
    ];

}
