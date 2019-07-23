<?php

include_once APPPATH . 'entities/Entity.php';

class IcomApontamento extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $data;
    protected $periodo;
    protected $id_cliente;
    protected $nome_cliente;
    protected $tipo_evento;
    protected $codigo_contato;
    protected $centro_custo;
    protected $colaboradores_alocados;
    protected $telefones_emails;
    protected $horario_inicio;
    protected $horario_termino;
    protected $total_horas;
    protected $custo_colaboradores;
    protected $custo_operacional;
    protected $impostos;
    protected $vaor_cobrado;
    protected $receita_liquida;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'data' => 'date',
        'periodo' => 'int',
        'id_cliente' => '?int',
        'tipo_evento' => 'string',
        'nome_cliente' => 'string',
        'codigo_contrato' => 'string',
        'centro_custo' => '?string',
        'colaboradores_alocados' => '?string',
        'telefones_emails' => '?string',
        'horario_inicio' => 'string',
        'horario_termino' => 'string',
        'total_horas' => 'string',
        'custo_colaboradores' => '?float',
        'custo_operacional' => '?float',
        'impostos' => '?float',
        'valor_cobrado' => '?float',
        'receita_liquida' => '?float'
    ];

}
