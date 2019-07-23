<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PesquisaLifoEstilos extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $indice_resposta;
    protected $estilo_personalidade_majoritario;
    protected $estilo_personalidade_secundario;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'indice_resposta' => 'int',
        'estilo_personalidade_majoritario' => '?string',
        'estilo_personalidade_secundario' => '?string'
    ];

}
