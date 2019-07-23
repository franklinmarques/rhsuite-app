<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RecrutamentoFormacao extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $id_escolaridade;
    protected $curso;
    protected $tipo;
    protected $instituicao;
    protected $ano_conclusao;
    protected $concluido;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'id_escolaridade' => 'int',
        'curso' => '?string',
        'tipo' => '?string',
        'instituicao' => 'string',
        'ano_conclusao' => '?int',
        'concluido' => 'int'
    ];

}
