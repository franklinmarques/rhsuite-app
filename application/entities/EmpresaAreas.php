<?php

include_once APPPATH . 'entities/Entity.php';

class EmpresaAreas extends Entity
{
	protected $id;
	protected $id_departamento;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'id_departamento' => '?int',
		'nome' => 'string'
	];

}
