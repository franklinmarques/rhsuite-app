<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Usuarioscursos extends Entity
{
    protected $id;
    protected $usuario;
    protected $curso;
    protected $datacadastro;
    protected $data_inicio;
    protected $data_maxima;
    protected $colaboradores_maximo;
    protected $nota_aprovacao;

    protected $casts = [
        'id' => 'int',
        'usuario' => 'int',
        'curso' => 'int',
        'datacadastro' => 'datetime',
        'data_inicio' => '?datetime',
        'data_maxima' => '?datetime',
        'colaboradores_maximo' => '?int',
        'nota_aprovacao' => '?int'
    ];

}
