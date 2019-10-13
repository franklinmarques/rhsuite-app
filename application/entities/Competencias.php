<?php

include_once APPPATH . 'entities/Entity.php';

class Competencias extends Entity
{
	protected $id;
	protected $nome;
	protected $id_usuario_EMPRESA;
	protected $id_cargo;
	protected $descricao;
	protected $data_inicio;
	protected $data_termino;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_usuario_EMPRESA' => 'int',
		'id_cargo' => 'int',
		'descricao' => 'string',
		'data_inicio' => 'datetime',
		'data_termino' => 'datetime',
		'status' => 'int'
	];

}
