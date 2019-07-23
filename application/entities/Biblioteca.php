<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Biblioteca extends Entity
{
    protected $id;
    protected $usuario;
    protected $tipo;
    protected $categoria;
    protected $titulo;
    protected $descricao;
    protected $link;
    protected $disciplina;
    protected $anoserie;
    protected $temacurricular;
    protected $uso;
    protected $licenca;
    protected $produzidopor;
    protected $tags;
    protected $datacadastro;
    protected $dataeditado;

    protected $casts = [
        'id' => 'int',
        'usuario' => 'int',
        'tipo' => 'int',
        'categoria' => 'int',
        'titulo' => 'string',
        'descricao' => 'string',
        'link' => 'string',
        'disciplina' => 'string',
        'anoserie' => 'string',
        'temacurricular' => 'string',
        'uso' => 'string',
        'licenca' => 'string',
        'produzidopor' => 'string',
        'tags' => 'string',
        'datacadastro' => 'datetime',
        'dataeditado' => 'datetime'
    ];

}
