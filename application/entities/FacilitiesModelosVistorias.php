<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesModelosVistorias extends Entity
{
	protected $id;
	protected $id_modelo;
	protected $id_vistoria;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_modelo' => 'int',
		'id_vistoria' => 'int',
		'status' => '?int'
	];

}
