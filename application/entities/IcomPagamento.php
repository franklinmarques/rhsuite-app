<?php

include_once APPPATH . 'entities/Entity.php';

class IcomPagamento extends Entity
{
	protected $id;
	protected $id_profissional_alocado;
	protected $nota_fiscal;
	protected $mes_referencia;
	protected $ano_referencia;
	protected $cnpj;
	protected $tipo_pagamento;
	protected $valor_total;
	protected $data_emissao;
	protected $assinatura;

	protected $casts = [
		'id' => 'int',
		'id_profissional_alocado' => 'int',
		'nota_fiscal' => 'string',
		'mes_referencia' => 'int',
		'ano_referencia' => 'int',
		'cnpj' => '?string',
		'tipo_pagamento' => '?strng',
		'valor_total' => 'float',
		'data_emissao' => '?datetime',
		'assinatura' => '?string'
	];

}
