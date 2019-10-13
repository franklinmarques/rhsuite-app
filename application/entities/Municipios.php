<?php

include_once APPPATH . 'entities/Entity.php';

class Municipios extends Entity
{
	protected $cod_mun;
	protected $cod_uf;
	protected $municipio;

	protected $casts = [
		'cod_mun' => 'int',
		'cod_uf' => 'int',
		'municipio' => 'string'
	];

}
