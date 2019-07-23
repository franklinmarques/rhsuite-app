<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CargosDimensao extends Entity
{
    protected $id;
    protected $nome;
    protected $cargo_competencia;
    protected $nivel;
    protected $peso;
    protected $atitude;
    protected $id_dimensao;

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'cargo_competencia' => 'int',
        'nivel' => 'int',
        'peso' => 'int',
        'atitude' => 'int',
        'id_dimensao' => '?int'
    ];

}
