<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesManutencoes extends Entity
{
	protected $id;
	protected $id_item;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'id_item' => 'int',
		'nome' => 'string'
	];

}
