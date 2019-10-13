<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesContasDespesas extends Entity
{
	protected $id;
	protected $id_item;
	protected $nome;
	protected $valor;
	protected $data_vencimento;
	protected $mes;
	protected $ano;

	protected $casts = [
		'id' => 'int',
		'id_item' => 'int',
		'nome' => 'string',
		'valor' => 'float',
		'data_vencimento' => 'date',
		'mes' => 'int',
		'ano' => 'int'
	];

}
