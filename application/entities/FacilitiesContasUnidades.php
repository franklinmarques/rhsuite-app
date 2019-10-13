<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesContasUnidades extends Entity
{
	protected $id;
	protected $id_conta_empresa;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'id_conta_empresa' => 'int',
		'nome' => 'string'
	];

}
