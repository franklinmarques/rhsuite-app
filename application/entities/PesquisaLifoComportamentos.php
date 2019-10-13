<?php

include_once APPPATH . 'entities/Entity.php';

class PesquisaLifoComportamentos extends Entity
{
	protected $id;
	protected $id_estilo;
	protected $situacao_comportamental;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'id_estilo' => 'int',
		'situacao_comportamental' => 'string',
		'nome' => 'string'
	];

}
