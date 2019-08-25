<?php

include_once APPPATH . 'entities/Entity.php';

class IcomSessoes extends Entity
{
    protected $id;
    protected $id_produto;
    protected $codigo_contrato;
    protected $data_evento;
    protected $horario_inicio;
    protected $horario_termino;
    protected $qtde_horas;
    protected $local_evento;
    protected $valor_faturamento;
    protected $valor_desconto;
    protected $custo_operacional;
    protected $custo_impostos;
    protected $profissional_alocado;
    protected $valor_pagamento_profissional;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_produto' => 'int',
        'codigo_contrato' => 'int',
        'data_evento' => 'datetime',
        'horario_inicio' => 'datetime',
        'horario_termino' => 'datetime',
        'qtde_horas' => 'int',
        'local_evento' => '?string',
        'valor_faturamento' => '?float',
        'valor_desconto' => '?float',
        'custo_operacional' => '?float',
        'custo_impostos' => '?float',
        'profissional_alocado' => 'string',
        'valor_pagamento_profissional' => '?float',
        'observacoes' => '?string'
    ];
}