<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiEscolasCursos extends Entity
{
    protected $id;
    protected $id_escola;
    protected $id_curso;
    protected $id_diretoria_curso;

    protected $casts = [
        'id' => 'int',
        'id_escola' => 'int',
        'id_curso' => 'int',
        'id_diretoria_curso' => '?int'
    ];

}
