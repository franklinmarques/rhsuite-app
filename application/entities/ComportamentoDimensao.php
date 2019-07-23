<?php

namespace App\Entities;

use CodeIgniter\Entity;

class ComportamentoDimensao extends Entity
{
    protected $id;
    protected $nome;
    protected $nivel;
    protected $peso;
    protected $status;
    protected $atitude;
    protected $id_competencia;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'nivel' => 'int',
        'peso' => 'string',
        'status' => 'int',
        'atitude' => 'int',
        'id_competencia' => 'int'
    ];

}
