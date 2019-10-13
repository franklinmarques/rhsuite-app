<?php

include_once APPPATH . 'entities/Entity.php';

class EiFuncoesSupervisionadas extends Entity
{
	protected $id;
	protected $id_supervisor;
	protected $cargo;
	protected $funcao;

	protected $casts = [
		'id' => 'int',
		'id_supervisor' => 'int',
		'cargo' => 'int',
		'funcao' => 'int'
	];

}
