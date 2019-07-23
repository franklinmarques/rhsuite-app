<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Usuariospaginas extends Entity
{
    protected $id;
    protected $usuario;
    protected $curso;
    protected $pagina;
    protected $datacadastro;
    protected $dataconclusao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'usuario' => 'int',
        'curso' => 'int',
        'pagina' => 'int',
        'datacadastro' => 'datetime',
        'dataconclusao' => '?datetime',
        'status' => 'int'
    ];

}
