<?php

include_once APPPATH . 'entities/Entity.php';

class CdEventos extends Entity
{
	protected $id;
	protected $codigo;
	protected $nome;
	protected $id_empresa;

	protected $casts = [
		'id' => 'int',
		'codigo' => 'string',
		'nome' => 'string',
		'id_empresa' => 'int'
	];

}
