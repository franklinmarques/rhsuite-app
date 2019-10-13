<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesSalas extends Entity
{
	protected $id;
	protected $id_andar;
	protected $sala;

	protected $casts = [
		'id' => 'int',
		'id_andar' => 'int',
		'sala' => 'string'
	];

}
