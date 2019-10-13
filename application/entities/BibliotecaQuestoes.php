<?php

include_once APPPATH . 'entities/Entity.php';

class Bibliotecaquestoes extends Entity
{
	protected $id;
	protected $id_pagina;
	protected $id_atividadeperguntas;
	protected $id_usuario;

	protected $casts = [
		'id' => 'int',
		'id_pagina' => 'int',
		'id_atividadeperguntas' => 'int',
		'id_usuario' => 'int'
	];

}
