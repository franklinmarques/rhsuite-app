<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Acessocurso extends Entity
{
    protected $id;
    protected $curso;
    protected $pagina;
    protected $usuario;
    protected $data_acesso;
    protected $data_saida;

    protected $casts = [
        'id' => 'int',
        'curso' => 'int',
        'pagina' => 'int',
        'usuario' => 'int',
        'data_acesso' => '?datetime',
        'data_saida' => '?datetime'
    ];

}
