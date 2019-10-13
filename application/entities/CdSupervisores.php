<?php

include_once APPPATH . 'entities/Entity.php';

class CdSupervisores extends Entity
{
	protected $id;
	protected $id_supervisor;
	protected $id_escola;
	protected $turno;

	protected $casts = [
		'id' => 'int',
		'id_supervisor' => 'int',
		'id_escola' => 'int',
		'turno' => 'string'
	];

}
