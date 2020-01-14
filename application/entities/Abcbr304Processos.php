<?php

include_once APPPATH . 'entities/Entity.php';

class Abcbr304Processos extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_menu;
	protected $url_pagina;
	protected $orientacoes_gerais;
	protected $nome_processo_1;
	protected $nome_processo_2;
	protected $arquivo_processo_1;
	protected $arquivo_processo_2;
	protected $nome_documentacao_1;
	protected $nome_documentacao_2;
	protected $arquivo_documentacao_1;
	protected $arquivo_documentacao_2;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_menu' => '?int',
		'url_pagina' => 'string',
		'orientacoes_gerais' => 'string',
		'nome_processo_1' => '?string',
		'nome_processo_2' => '?string',
		'arquivo_processo_1' => '?string',
		'arquivo_processo_2' => '?string',
		'nome_documentacao_1' => '?string',
		'nome_documentacao_2' => '?string',
		'arquivo_documentacao_1' => '?string',
		'arquivo_documentacao_2' => '?string'
	];

}
