<?php

include_once APPPATH . 'entities/Entity.php';

class Documentos extends Entity
{
	protected $id;
	protected $datacadastro;
	protected $colaborador;
	protected $tipo;
	protected $descricao;
	protected $arquivo;
	protected $usuario;

	protected $casts = [
		'id' => 'int',
		'datacadastro' => 'datetime',
		'colaborador' => '?int',
		'tipo' => '?int',
		'descricao' => 'string',
		'arquivo' => '?string',
		'usuario' => 'int'
	];

}
