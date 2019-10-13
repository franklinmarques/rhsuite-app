<?php

include_once APPPATH . 'entities/Entity.php';

class PdiDesenvolvimento extends Entity
{
	protected $id;
	protected $id_pdi;
	protected $competencia;
	protected $descricao;
	protected $expectativa;
	protected $resultado;
	protected $data_inicio;
	protected $data_termino;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_pdi' => 'int',
		'competencia' => 'string',
		'descricao' => 'string',
		'expectativa' => 'string',
		'resultado' => 'string',
		'data_inicio' => 'datetime',
		'data_termino' => 'datetime',
		'status' => '?string'
	];

}
