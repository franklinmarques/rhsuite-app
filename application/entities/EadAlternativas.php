<?php

include_once APPPATH . 'entities/Entity.php';

class EadAlternativas extends Entity
{
	protected $id;
	protected $id_questao;
	protected $alternativa;
	protected $peso;
	protected $id_copia;

	protected $casts = [
		'id' => 'int',
		'id_questao' => 'int',
		'alternativa' => 'string',
		'peso' => 'int',
		'id_copia' => '?int'
	];

}
