<?php

include_once APPPATH . 'entities/Entity.php';

class CompetenciasDimensao extends Entity
{
	protected $id;
	protected $nome;
	protected $id_modelo;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_modelo' => 'int'
	];

}
