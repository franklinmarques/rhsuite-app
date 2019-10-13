<?php

include_once APPPATH . 'entities/Entity.php';

class Pdi extends Entity
{
	protected $id;
	protected $usuario;
	protected $nome;
	protected $descricao;
	protected $data_inicio;
	protected $data_termino;
	protected $observacao;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'usuario' => 'int',
		'nome' => 'string',
		'descricao' => '?string',
		'data_inicio' => '?datetime',
		'data_termino' => '?datetime',
		'observacao' => '?string',
		'status' => '?string'
	];

}
