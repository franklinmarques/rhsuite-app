<?php

include_once APPPATH . 'entities/Entity.php';

class RequisicoesPessoalEstagios extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $nome;
	protected $destino_email;
	protected $email_responsavel;
	protected $mensagem;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'nome' => 'string',
		'destino_email' => 'string',
		'email_responsavel' => 'string',
		'mensagem' => '?string'
	];

}
