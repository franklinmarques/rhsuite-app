<?php

include_once APPPATH . 'entities/Entity.php';

class AssessmentsAlternativas extends Entity
{
	protected $id;
	protected $id_modelo;
	protected $id_pergunta;
	protected $alternativa;
	protected $peso;

	protected $casts = [
		'id' => 'int',
		'id_modelo' => 'int',
		'id_pergunta' => '?int',
		'alternativa' => 'string',
		'peso' => '?int'
	];

}
