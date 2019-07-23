<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiDisciplinas extends Entity
{
    protected $id;
    protected $id_curso;
    protected $nome;

    protected $casts = [
        'id' => 'int',
        'id_curso' => 'int',
        'nome' => 'string'
    ];

}
