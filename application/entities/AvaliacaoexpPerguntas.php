<?php

include_once APPPATH . 'entities/Entity.php';

class AvaliacaoexpPerguntas extends Entity
{
	protected $id;
	protected $id_modelo;
	protected $pergunta;
	protected $tipo;

	protected $casts = [
		'id' => 'int',
		'id_modelo' => 'int',
		'pergunta' => 'string',
		'tipo' => 'string'
	];

}
