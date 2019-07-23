<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesVistorias extends Entity
{
    protected $id;
    protected $id_item;
    protected $nome;
    protected $periodicidade_mensal;

    protected $casts = [
        'id' => 'int',
        'id_item' => 'int',
        'nome' => 'string',
        'periodicidade_mensal' => 'int'
    ];

}
