<?php

include_once APPPATH . 'entities/Entity.php';

class PapdAtividades extends Entity
{
	protected $id;
	protected $nome;
	protected $valor;
	protected $id_instituicao;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'valor' => 'float',
		'id_instituicao' => 'int'
	];

}
