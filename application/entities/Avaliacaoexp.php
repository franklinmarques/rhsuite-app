<?php

include_once APPPATH . 'entities/Entity.php';

class Avaliacaoexp extends Entity
{
	protected $id;
	protected $nome;
	protected $id_modelo;
	protected $data_inicio;
	protected $data_termino;
	protected $ativo;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_modelo' => 'int',
		'data_inicio' => 'date',
		'data_termino' => 'date',
		'ativo' => 'int'
	];

}
