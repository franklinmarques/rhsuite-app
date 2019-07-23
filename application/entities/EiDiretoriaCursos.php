<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiDiretoriaCursos extends Entity
{
    protected $id;
    protected $id_diretoria;
    protected $id_curso;

    protected $casts = [
        'id' => 'int',
        'id_diretoria' => 'int',
        'id_curso' => 'int'
    ];

}
