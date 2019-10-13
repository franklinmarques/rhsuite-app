<?php

include_once APPPATH . 'entities/Entity.php';

class EmailAvaliacao extends Entity
{
	protected $id;
	protected $id_avaliacao;
	protected $texto_inicio;
	protected $texto_cobranca;
	protected $texto_fim;

	protected $casts = [
		'id' => 'int',
		'id_avaliacao' => 'int',
		'texto_inicio' => 'string',
		'texto_cobranca' => 'string',
		'texto_fim' => 'string'
	];

}
