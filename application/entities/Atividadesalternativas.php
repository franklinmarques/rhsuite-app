<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Atividadesalternativas extends Entity
{
    protected $id;
    protected $quiz;
    protected $alternativa;
    protected $correta;

    protected $casts = [
        'id' => 'int',
        'quiz' => 'int',
        'alternativa' => 'string',
        'correta' => '?int'
    ];

}