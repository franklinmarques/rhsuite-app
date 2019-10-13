<?php

include_once APPPATH . 'entities/Entity.php';

class PesquisaResultado extends Entity
{
	protected $id;
	protected $id_avaliador;
	protected $id_pergunta;
	protected $id_alternativa;
	protected $valor;
	protected $resposta;
	protected $data_avaliacao;

	protected $casts = [
		'id' => 'int',
		'id_avaliador' => 'int',
		'id_pergunta' => 'int',
		'id_alternativa' => '?int',
		'valor' => '?int',
		'resposta' => '?string',
		'data_avaliacao' => 'datetime'
	];

}
