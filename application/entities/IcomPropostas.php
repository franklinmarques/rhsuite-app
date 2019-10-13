<?php

include_once APPPATH . 'entities/Entity.php';

class IcomPropostas extends Entity
{
	protected $codigo;
	protected $id_cliente;
	protected $id_setor;
	protected $descricao;
	protected $data_entrega;
	protected $probabilidade_fechamento;
	protected $valor;
	protected $status;
	protected $custo_produto_servico;
	protected $custo_administrativo;
	protected $impostos;
	protected $margem_liquida;
	protected $margem_liquida_percentual;
	protected $detalhes;
	protected $arquivo;

	protected $casts = [
		'codigo' => 'int',
		'id_cliente' => 'int',
		'id_setor' => 'int',
		'descricao' => 'string',
		'data_entrega' => 'date',
		'probabilidade_fechamento' => '?int',
		'valor' => 'float',
		'status' => 'string',
		'custo_produto_servico' => '?float',
		'custo_administrativo' => '?float',
		'impostos' => '?float',
		'margem_liquida' => '?float',
		'margem_liquida_percentual' => '?int',
		'detalhes' => '?string',
		'arquivo' => '?string'
	];

}
