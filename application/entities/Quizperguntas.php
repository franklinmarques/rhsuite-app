<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Quizperguntas extends Entity
{
    protected $id;
    protected $pagina;
    protected $tipo;
    protected $pergunta;
    protected $respostacorreta;
    protected $respostaerrada;
    protected $copia_de;

    protected $casts = [
        'id' => 'int',
        'pagina' => 'int',
        'tipo' => 'int',
        'pergunta' => 'string',
        'respostacorreta' => 'string',
        'respostaerrada' => 'string',
        'copia_de' => 'int'
    ];

}
