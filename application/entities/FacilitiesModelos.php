<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesModelos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_facility_empresa;
    protected $nome;
    protected $tipo;
    protected $versao;
    protected $status;
    protected $id_copia;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_facility_empresa' => 'int',
        'nome' => 'string',
        'tipo' => '?string',
        'versao' => 'string',
        'status' => 'int',
        'id_copia' => '?int'
    ];

}
