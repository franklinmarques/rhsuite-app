<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RequisicoesPessoalTestes extends Entity
{
    protected $id;
    protected $id_candidato;
    protected $tipo_teste;
    protected $id_modelo;
    protected $nome;
    protected $data_inicio;
    protected $data_termino;
    protected $minutos_duracao;
    protected $aleatorizacao;
    protected $data_acesso;
    protected $data_envio;
    protected $nota_aproveitamento;
    protected $observacoes;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_candidato' => 'int',
        'tipo_teste' => 'string',
        'id_modelo' => '?int',
        'nome' => '?string',
        'data_inicio' => 'datetime',
        'data_termino' => '?datetime',
        'minutos_duracao' => '?int',
        'aleatorizacao' => '?string',
        'data_acesso' => '?datetime',
        'data_envio' => '?datetime',
        'nota_aproveitamento' => '?float',
        'observacoes' => '?string',
        'status' => '?string'
    ];

}
