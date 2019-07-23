<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CandidatosFormacoes extends Entity
{
    protected $id;
    protected $id_candidato;
    protected $id_escolaridade;
    protected $curso;
    protected $tipo;
    protected $instituicao;
    protected $ano_conclusao;
    protected $concluido;

    protected $casts = [
        'id' => 'int',
        'id_candidato' => 'int',
        'id_escolaridade' => 'int',
        'curso' => '?string',
        'tipo' => '?string',
        'instituicao' => 'string',
        'ano_conclusao' => '?int',
        'concluido' => 'int'
    ];

}
