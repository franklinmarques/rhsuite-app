<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosResultado extends Entity
{
    protected $id;
    protected $id_acesso;
    protected $id_questao;
    protected $id_alternativa;
    protected $valor;
    protected $resposta;
    protected $data_avaliacao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_acesso' => 'int',
        'id_questao' => 'int',
        'id_alternativa' => '?int',
        'valor' => '?int',
        'resposta' => '?string',
        'data_avaliacao' => 'datetime',
        'status' => 'int'
    ];

}
