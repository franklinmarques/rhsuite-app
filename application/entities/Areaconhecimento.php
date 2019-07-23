<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Areaconhecimento extends Entity
{
    protected $Id;
    protected $area_conhecimento;

    protected $casts = [
        'Id' => 'int',
        'area_conhecimento' => '?string'
    ];

}
