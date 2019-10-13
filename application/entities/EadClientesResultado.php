<?php

include_once APPPATH . 'entities/Entity.php';

class EadClientesResultado extends Entity
{
	protected $id;
	protected $id_acesso;
	protected $id_questao;
	protected $id_alternativa;
	protected $valor;
	protected $resposta;
	protected $nota;
	protected $data_avaliacao;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_acesso' => 'int',
		'id_questao' => 'int',
		'id_alternativa' => '?int',
		'valor' => '?int',
		'resposta' => '?string',
		'nota' => '?int',
		'data_avaliacao' => 'datetime',
		'status' => 'int'
	];

}
