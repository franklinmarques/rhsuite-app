<?php

include_once APPPATH . 'entities/Entity.php';

class EadPilulasColaboradores extends Entity
{
	protected $id;
	protected $id_pilula;
	protected $id_usuario;

	protected $casts = [
		'id' => 'int',
		'id_pilula' => 'int',
		'id_usuario' => 'int'
	];

}
