<?php

namespace App\Entities;

use CodeIgniter\Entity;

class GestaoVagas extends Entity
{
    protected $codigo;
    protected $id_empresa;
    protected $data_abertura;
    protected $status;
    protected $id_requisicao_pessoal;
    protected $id_cargo;
    protected $id_funcao;
    protected $formacao_minima;
    protected $formacao_especifica_minima;
    protected $perfil_profissional_desejado;
    protected $quantidade;
    protected $estado_vaga;
    protected $cidade_vaga;
    protected $bairro_vaga;
    protected $tipo_vinculo;
    protected $remuneracao;
    protected $beneficios;
    protected $horario_trabalho;
    protected $contato_selecionador;

    protected $casts = [
        'codigo' => 'int',
        'id_empresa' => 'int',
        'data_abertura' => 'datetime',
        'status' => 'int',
        'id_requisicao_pessoal' => 'int',
        'id_cargo' => 'int',
        'id_funcao' => 'int',
        'formacao_minima' => '?int',
        'formacao_especifica_minima' => '?string',
        'perfil_profissional_desejado' => '?string',
        'quantidade' => 'int',
        'estado_vaga' => '?string',
        'cidade_vaga' => '?string',
        'bairro_vaga' => '?string',
        'tipo_vinculo' => 'int',
        'remuneracao' => 'float',
        'beneficios' => '?string',
        'horario_trabalho' => '?string',
        'contato_selecionador' => '?string'
    ];

}
