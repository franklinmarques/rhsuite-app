<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RecrutamentoGoogleOld extends Entity
{
    protected $id;
    protected $data_captacao;
    protected $cliente;
    protected $cargo;
    protected $cidade;
    protected $nome;
    protected $deficiencia;
    protected $telefone;
    protected $email;
    protected $fonte_contratacao;
    protected $status;
    protected $status_obs;
    protected $data_entrevista_rh;
    protected $resultado_entrevista_rh;
    protected $data_entrevista_cliente;
    protected $resultado_entrevista_cliente;
    protected $observacoes;
    protected $data_rh;
    protected $data_cli;

    protected $casts = [
        'id' => 'int',
        'data_captacao' => 'string',
        'cliente' => '?string',
        'cargo' => '?string',
        'cidade' => '?string',
        'nome' => 'string',
        'deficiencia' => '?string',
        'telefone' => '?string',
        'email' => '?string',
        'fonte_contratacao' => '?string',
        'status' => '?string',
        'status_obs' => '?string',
        'data_entrevista_rh' => '?string',
        'resultado_entrevista_rh' => '?string',
        'data_entrevista_cliente' => '?string',
        'resultado_entrevista_cliente' => '?string',
        'observacoes' => '?string',
        'data_rh' => '?datetime',
        'data_cli' => '?datetime'
    ];

}
