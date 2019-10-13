<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesModelosManutencoes extends Entity
{
	protected $id;
	protected $id_modelo;
	protected $id_manutencao;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_modelo' => 'int',
		'id_manutencao' => 'int',
		'status' => '?int'
	];

}
