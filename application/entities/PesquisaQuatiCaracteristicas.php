<?php

include_once APPPATH . 'entities/Entity.php';

class PesquisaQuatiCaracteristicas extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $tipo_comportamental;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'tipo_comportamental' => 'string',
		'nome' => 'string'
	];

}
