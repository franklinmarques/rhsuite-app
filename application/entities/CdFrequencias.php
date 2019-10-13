<?php

include_once APPPATH . 'entities/Entity.php';

class CdFrequencias extends Entity
{
	protected $id;
	protected $id_matriculado;
	protected $data;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_matriculado' => 'int',
		'data' => 'date',
		'status' => '?string'
	];

}
