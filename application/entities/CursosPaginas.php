<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosPaginas extends Entity
{
    protected $id;
    protected $id_curso;
    protected $ordem;
    protected $modulo;
    protected $titulo;
    protected $conteudo;
    protected $pdf;
    protected $url;
    protected $arquivo_video;
    protected $categoriabiblioteca;
    protected $titulobiblioteca;
    protected $tagsbiblioteca;
    protected $biblioteca;
    protected $audio;
    protected $video;
    protected $autoplay;
    protected $nota_corte;
    protected $id_pagina_aprovacao;
    protected $id_pagina_reprovacao;
    protected $aleatorizacao;
    protected $data_cadastro;
    protected $data_editado;
    protected $id_copia;

    protected $casts = [
        'id' => 'int',
        'id_curso' => 'int',
        'ordem' => 'int',
        'modulo' => 'string',
        'titulo' => 'string',
        'conteudo' => '?string',
        'pdf' => '?string',
        'url' => '?string',
        'arquivo_video' => '?string',
        'categoriabiblioteca' => '?int',
        'titulobiblioteca' => '?string',
        'tagsbiblioteca' => '?string',
        'biblioteca' => '?int',
        'audio' => '?string',
        'video' => '?string',
        'autoplay' => 'int',
        'nota_corte' => '?int',
        'id_pagina_aprovacao' => '?int',
        'id_pagina_reprovacao' => '?int',
        'aleatorizacao' => '?string',
        'data_cadastro' => 'datetime',
        'data_editado' => '?datetime',
        'id_copia' => '?int'
    ];

}
