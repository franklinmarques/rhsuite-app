<?php

include_once APPPATH . 'entities/Entity.php';

class EiSupervisores extends Entity
{
	protected $id;
	protected $id_escola;
	protected $id_coordenacao;
	protected $id_supervisor;
	protected $turno;

	protected $casts = [
		'id' => 'int',
		'id_escola' => 'int',
		'id_coordenacao' => 'int',
		'id_supervisor' => 'int',
		'turno' => '?string'
	];

}
