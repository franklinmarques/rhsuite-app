<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Paginas extends Entity
{
    protected $id;
    protected $curso;
    protected $ordem;
    protected $modulo;
    protected $titulo;
    protected $conteudo;
    protected $pdf;
    protected $youtube;
    protected $categoriabiblioteca;
    protected $titulobiblioteca;
    protected $tagsbiblioteca;
    protected $biblioteca;
    protected $audio;
    protected $video;
    protected $arquivoVideo;
    protected $nota_corte;
    protected $id_pagina_acerto;
    protected $id_pagina_erro;
    protected $datacadastro;
    protected $dataeditado;
    protected $copia_de;

    protected $casts = [
        'id' => 'int',
        'curso' => 'int',
        'ordem' => 'int',
        'modulo' => 'string',
        'titulo' => 'string',
        'conteudo' => 'string',
        'pdf' => 'string',
        'youtube' => '?string',
        'categoriabiblioteca' => 'int',
        'titulobiblioteca' => 'string',
        'tagsbiblioteca' => 'string',
        'biblioteca' => 'int',
        'audio' => '?string',
        'video' => '?string',
        'arquivoVideo' => '?string',
        'nota_corte' => '?int',
        'id_pagina_acerto' => '?int',
        'id_pagina_erro' => '?int',
        'datacadastro' => 'datetime',
        'dataeditado' => 'datetime',
        'copia_de' => 'int'
    ];

}
