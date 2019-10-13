<?php

include_once APPPATH . 'entities/Entity.php';

class EiMapaUnidades extends Entity
{
	protected $id;
	protected $id_alocacao;
	protected $id_supervisao;
	protected $id_escola;
	protected $escola;
	protected $municipio;

	protected $casts = [
		'id' => 'int',
		'id_alocacao' => 'int',
		'id_supervisao' => '?int',
		'id_escola' => '?int',
		'escola' => 'string',
		'municipio' => 'string'
	];

}
