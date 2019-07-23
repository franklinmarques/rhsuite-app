<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PesquisaPerguntas extends Entity
{
    protected $id;
    protected $id_modelo;
    protected $id_categoria;
    protected $pergunta;
    protected $tipo_resposta;
    protected $tipo_eneagrama;
    protected $prefixo_resposta;
    protected $justificativa;
    protected $valor_min;
    protected $valor_max;

    protected $casts = [
        'id' => 'int',
        'id_modelo' => 'int',
        'id_categoria' => '?int',
        'pergunta' => 'string',
        'tipo_resposta' => 'string',
        'tipo_eneagrama' => '?int',
        'prefixo_resposta' => '?string',
        'justificativa' => '?int',
        'valor_min' => '?int',
        'valor_max' => '?int'
    ];

}
