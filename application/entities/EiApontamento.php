<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiApontamento extends Entity
{
    protected $id;
    protected $id_alocado;
    protected $data;
    protected $periodo;
    protected $horario_inicio;
    protected $status;
    protected $id_usuario;
    protected $id_alocado_sub1;
    protected $id_alocado_sub2;
    protected $desconto;
    protected $desconto_sub1;
    protected $desconto_sub2;
    protected $ocorrencia_cuidador;
    protected $ocorrencia_aluno;
    protected $ocorrencia_professor;

    protected $casts = [
        'id' => 'int',
        'id_alocado' => 'int',
        'data' => 'datetime',
        'periodo' => '?int',
        'horario_inicio' => '?string',
        'status' => 'string',
        'id_usuario' => '?int',
        'id_alocado_sub1' => '?int',
        'id_alocado_sub2' => '?int',
        'desconto' => '?string',
        'desconto_sub1' => '?string',
        'desconto_sub2' => '?string',
        'ocorrencia_cuidador' => '?string',
        'ocorrencia_aluno' => '?string',
        'ocorrencia_professor' => '?string'
    ];

}
