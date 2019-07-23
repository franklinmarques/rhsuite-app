<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Mensagensenviadas extends Entity
{
    protected $id;
    protected $remetente;
    protected $destinatario;
    protected $titulo;
    protected $mensagem;
    protected $anexo;
    protected $datacadastro;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'remetente' => 'int',
        'destinatario' => 'int',
        'titulo' => '?string',
        'mensagem' => 'string',
        'anexo' => '?string',
        'datacadastro' => 'datetime',
        'status' => 'int'
    ];

}
