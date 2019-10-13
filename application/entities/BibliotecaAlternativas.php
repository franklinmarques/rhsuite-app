<?php

include_once APPPATH . 'entities/Entity.php';

class BibliotecaAlternativas extends Entity
{
	protected $id;
	protected $id_questao;
	protected $alternativa;
	protected $peso;

	protected $casts = [
		'id' => 'int',
		'id_questao' => 'int',
		'alternativa' => 'string',
		'peso' => 'int'
	];

}
