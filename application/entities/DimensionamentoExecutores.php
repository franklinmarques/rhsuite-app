<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoExecutores extends Entity
{
	protected $id;
	protected $id_crono_analise;
	protected $tipo;
	protected $id_equipe;
	protected $id_usuario;

	protected $casts = [
		'id' => 'int',
		'id_crono_analise' => 'int',
		'tipo' => 'string',
		'id_equipe' => '?int',
		'id_usuario' => '?int'
	];

}
