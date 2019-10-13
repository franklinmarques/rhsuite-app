<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesEmpresas extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'nome' => 'string'
	];

}
