<?php

include_once APPPATH . 'entities/Entity.php';

class PapdHipoteseDiagnostica extends Entity
{
	protected $id;
	protected $nome;
	protected $id_instituicao;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_instituicao' => 'int'
	];

}
