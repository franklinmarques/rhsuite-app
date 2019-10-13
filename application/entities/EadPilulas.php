<?php

include_once APPPATH . 'entities/Entity.php';

class EadPilulas extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_curso;
	protected $id_area_conhecimento;
	protected $publico;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_curso' => 'int',
		'id_area_conhecimento' => '?int',
		'publico' => 'int'
	];

}
