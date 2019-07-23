<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Usuariosatividades extends Entity
{
    protected $id;
    protected $usuario;
    protected $curso;
    protected $pagina;
    protected $atividade;
    protected $datacadastro;
    protected $dataconclusao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'usuario' => 'int',
        'curso' => 'int',
        'pagina' => 'int',
        'atividade' => '?int',
        'datacadastro' => 'datetime',
        'dataconclusao' => '?datetime',
        'status' => 'int'
    ];

}
