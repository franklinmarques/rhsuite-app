<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoAtividades extends Entity
{
	protected $id;
	protected $id_processo;
	protected $nome;

	protected $casts = [
		'id' => 'int',
		'id_processo' => 'int',
		'nome' => 'string'
	];

}
