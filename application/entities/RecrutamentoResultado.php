<?php

include_once APPPATH . 'entities/Entity.php';

class RecrutamentoResultado extends Entity
{
	protected $id;
	protected $id_teste;
	protected $id_pergunta;
	protected $peso_max;
	protected $id_alternativa;
	protected $valor;
	protected $resposta;
	protected $nota;
	protected $data_avaliacao;

	protected $casts = [
		'id' => 'int',
		'id_teste' => 'int',
		'id_pergunta' => 'int',
		'peso_max' => '?int',
		'id_alternativa' => '?int',
		'valor' => '?int',
		'resposta' => '?string',
		'nota' => '?int',
		'data_avaliacao' => '?datetime'
	];

}
