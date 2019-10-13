<?php

include_once APPPATH . 'entities/Entity.php';

class RecrutamentoModelos extends Entity
{
	protected $id;
	protected $nome;
	protected $id_usuario_EMPRESA;
	protected $tipo;
	protected $tipo_old;
	protected $observacoes;
	protected $instrucoes;
	protected $aleatorizacao;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_usuario_EMPRESA' => 'int',
		'tipo' => 'string',
		'tipo_old' => 'string',
		'observacoes' => '?string',
		'instrucoes' => '?string',
		'aleatorizacao' => '?string'
	];

}
