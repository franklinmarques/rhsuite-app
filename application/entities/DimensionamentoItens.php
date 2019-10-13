<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoItens extends Entity
{
	protected $id;
	protected $id_etapa;
	protected $nome;
	protected $descricao;
	protected $unidade_medida;
	protected $valor;

	protected $casts = [
		'id' => 'int',
		'id_etapa' => 'int',
		'nome' => 'string',
		'descricao' => '?string',
		'unidade_medida' => '?string',
		'valor' => '?float'
	];

}
