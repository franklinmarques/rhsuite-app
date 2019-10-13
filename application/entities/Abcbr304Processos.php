<?php

include_once APPPATH . 'entities/Entity.php';

class Abcbr304Processos extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_menu;
	protected $url_pagina;
	protected $orientacoes_gerais;
	protected $processo_1;
	protected $processo_2;
	protected $documentacao_1;
	protected $documentacao_2;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_menu' => '?int',
		'url_pagina' => 'string',
		'orientacoes_gerais' => 'string',
		'processo_1' => '?string',
		'processo_2' => '?string',
		'documentacao_1' => '?string',
		'documentacao_2' => '?string'
	];

}
