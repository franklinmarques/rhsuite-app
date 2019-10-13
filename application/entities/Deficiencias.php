<?php

include_once APPPATH . 'entities/Entity.php';

class Deficiencias extends Entity
{
	protected $id;
	protected $tipo;

	protected $casts = [
		'id' => 'int',
		'tipo' => 'string'
	];

}
