<?php

include_once APPPATH . 'entities/Entity.php';

class Estados extends Entity
{
	protected $cod_uf;
	protected $estado;
	protected $uf;

	protected $casts = [
		'cod_uf' => 'int',
		'estado' => 'string',
		'uf' => 'string'
	];

}
