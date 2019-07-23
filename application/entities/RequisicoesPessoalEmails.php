<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RequisicoesPessoalEmails extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $colaborador;
    protected $email;
    protected $tipo_usuario;
    protected $tipo_email;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => '?int',
        'colaborador' => 'string',
        'email' => 'string',
        'tipo_usuario' => 'int',
        'tipo_email' => '?int'
    ];

}
