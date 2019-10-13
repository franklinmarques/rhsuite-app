<?php

include_once APPPATH . 'entities/Entity.php';

class Pesquisa extends Entity
{
	protected $id;
	protected $nome;
	protected $id_modelo;
	protected $data_inicio;
	protected $data_termino;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_modelo' => 'int',
		'data_inicio' => 'datetime',
		'data_termino' => 'datetime'
	];

}
