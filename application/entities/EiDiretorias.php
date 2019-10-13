<?php

include_once APPPATH . 'entities/Entity.php';

class EiDiretorias extends Entity
{
	protected $id;
	protected $nome;
	protected $alias;
	protected $id_empresa;
	protected $depto;
	protected $municipio;
	protected $id_coordenador;
	protected $senha_exclusao;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'alias' => '?string',
		'id_empresa' => 'int',
		'depto' => 'string',
		'municipio' => 'string',
		'id_coordenador' => '?int',
		'senha_exclusao' => '?string'
	];

}
