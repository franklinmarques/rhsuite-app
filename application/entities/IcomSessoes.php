<?php

include_once APPPATH . 'entities/Entity.php';

class IcomSessoes extends Entity
{
	protected $id;
	protected $id_produto;
	protected $id_cliente;
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
	protected $id_depto_prestador_servico;
	protected $id_profissional_alocado;
	protected $valor_pagamento_profissional;
	protected $observacoes;

	protected $casts = [
		'id' => 'int',
		'id_produto' => 'int',
		'id_cliente' => 'int',
		'codigo_contrato' => '?int',
		'data_evento' => 'date',
		'horario_inicio' => 'time',
		'horario_termino' => 'time',
		'qtde_horas' => 'float',
		'local_evento' => '?string',
		'valor_faturamento' => '?float',
		'valor_desconto' => '?float',
		'custo_operacional' => '?float',
		'custo_impostos' => '?float',
		'id_depto_prestador_servico' => 'int',
		'id_profissional_alocado' => 'int',
		'valor_pagamento_profissional' => '?float',
		'observacoes' => '?string'
	];

}
