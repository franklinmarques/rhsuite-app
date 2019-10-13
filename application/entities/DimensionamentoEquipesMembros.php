<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoEquipesMembros extends Entity
{
	protected $id;
	protected $id_equipe;
	protected $id_usuario;

	protected $casts = [
		'id' => 'int',
		'id_equipe' => 'int',
		'id_usuario' => 'int'
	];

}
