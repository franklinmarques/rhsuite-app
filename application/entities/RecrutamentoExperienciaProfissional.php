<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RecrutamentoExperienciaProfissional extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $instituicao;
    protected $data_entrada;
    protected $data_saida;
    protected $cargo_entrada;
    protected $cargo_saida;
    protected $salario_entrada;
    protected $salario_saida;
    protected $motivo_saida;
    protected $realizacoes;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'instituicao' => 'string',
        'data_entrada' => 'datetime',
        'data_saida' => '?datetime',
        'cargo_entrada' => 'string',
        'cargo_saida' => '?string',
        'salario_entrada' => 'float',
        'salario_saida' => '?float',
        'motivo_saida' => '?string',
        'realizacoes' => '?string'
    ];

}