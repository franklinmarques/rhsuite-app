<?php

include_once APPPATH . 'entities/Entity.php';

class EmtuAlocados extends Entity
{
	protected $id;
	protected $id_alocacao;
	protected $id_usuario;
	protected $nome_usuario;
	protected $id_funcao;

	protected $casts = [
		'id' => 'int',
		'id_alocacao' => 'int',
		'id_usuario' => '?int',
		'nome_usuario' => 'string',
		'id_funcao' => '?int'
	];

}
