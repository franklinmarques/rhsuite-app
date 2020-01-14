<?php

include_once APPPATH . 'entities/Entity.php';

class IcomFaturamento extends Entity
{
	protected $id;
	protected $id_cliente;
	protected $conta_corrente;
	protected $mes_referencia;
	protected $ano_referencia;
	protected $cnpj;
	protected $endereco;
	protected $email;
	protected $contato;
	protected $condicoes_pagamento;
	protected $centro_custo;
	protected $total_sessoes;
	protected $valor_total;
	protected $data_emissao;
	protected $assinatura;

	protected $casts = [
		'id' => 'int',
		'id_cliente' => 'int',
		'conta_corrente' => '?string',
		'mes_referencia' => 'int',
		'ano_referencia' => 'int',
		'cnpj' => '?string',
		'endereco' => '?string',
		'email' => '?string',
		'contato' => '?string',
		'condicoes_pagamento' => '?string',
		'centro_custo' => '?string',
		'total_sessoes' => 'int',
		'valor_total' => 'float',
		'data_emissao' => '?datetime',
		'assinatura' => '?string'
	];

}
