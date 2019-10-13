<?php

include_once APPPATH . 'entities/Entity.php';

class Tipodocumento extends Entity
{
	protected $id;
	protected $datacadastro;
	protected $descricao;
	protected $categoria;
	protected $usuario;

	protected $casts = [
		'id' => 'int',
		'datacadastro' => 'datetime',
		'descricao' => 'string',
		'categoria' => '?int',
		'usuario' => 'int'
	];

}
