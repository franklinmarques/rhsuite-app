<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoCronoAnalises extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $nome;
	protected $id_processo;
	protected $data_inicio;
	protected $data_termino;
	protected $status;
	protected $base_tempo;
	protected $unidade_producao;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'nome' => 'string',
		'id_processo' => '?int',
		'data_inicio' => 'date',
		'data_termino' => 'date',
		'status' => '?string',
		'base_tempo' => '?string',
		'unidade_producao' => '?string'
	];

}
