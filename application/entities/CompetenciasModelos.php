<?php

include_once APPPATH . 'entities/Entity.php';

class CompetenciasModelos extends Entity
{
	protected $id;
	protected $nome;
	protected $tipo;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'tipo' => 'string'
	];

}
