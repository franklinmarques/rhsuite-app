<?php

include_once APPPATH . 'entities/Entity.php';

class Escolaridade extends Entity
{
	protected $id;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string'
	];

}
