<?php

include_once APPPATH . 'entities/Entity.php';

class CompetenciasAvaliados extends Entity
{
	protected $id;
	protected $id_competencia;
	protected $id_usuario;

	protected $casts = [
		'id' => 'int',
		'id_competencia' => 'int',
		'id_usuario' => 'int'
	];

}
