<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PapdPacientes extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $nome;
    protected $cpf;
    protected $data_nascimento;
    protected $sexo;
    protected $id_deficiencia;
    protected $cadastro_municipal;
    protected $id_hipotese_diagnostica;
    protected $logradouro;
    protected $numero;
    protected $complemento;
    protected $bairro;
    protected $cidade;
    protected $cidade_nome;
    protected $estado;
    protected $cep;
    protected $nome_responsavel_1;
    protected $telefone_fixo_1;
    protected $nome_responsavel_2;
    protected $telefone_fixo_2;
    protected $telefone_celular_2;
    protected $data_ingresso;
    protected $data_inativo;
    protected $data_fila_espera;
    protected $data_afastamento;
    protected $contratante;
    protected $contrato;
    protected $id_instituicao;
    protected $status;
    protected $telefone_celular_1;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'nome' => 'string',
        'cpf' => '?string',
        'data_nascimento' => 'datetime',
        'sexo' => 'string',
        'id_deficiencia' => '?int',
        'cadastro_municipal' => '?string',
        'id_hipotese_diagnostica' => '?int',
        'logradouro' => '?string',
        'numero' => '?int',
        'complemento' => '?string',
        'bairro' => '?string',
        'cidade' => '?int',
        'cidade_nome' => '?string',
        'estado' => '?int',
        'cep' => '?string',
        'nome_responsavel_1' => '?string',
        'telefone_fixo_1' => '?string',
        'nome_responsavel_2' => '?string',
        'telefone_fixo_2' => '?string',
        'telefone_celular_2' => '?string',
        'data_ingresso' => 'datetime',
        'data_inativo' => '?datetime',
        'data_fila_espera' => '?datetime',
        'data_afastamento' => '?datetime',
        'contratante' => '?string',
        'contrato' => '?string',
        'id_instituicao' => 'int',
        'status' => 'string',
        'telefone_celular_1' => '?string'
    ];

}
