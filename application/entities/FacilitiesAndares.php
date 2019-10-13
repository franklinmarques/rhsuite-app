<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesAndares extends Entity
{
	protected $id;
	protected $id_unidade;
	protected $andar;

	protected $casts = [
		'id' => 'int',
		'id_unidade' => 'int',
		'andar' => 'string'
	];

}
