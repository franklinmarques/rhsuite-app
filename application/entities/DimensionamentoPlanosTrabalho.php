<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoPlanosTrabalho extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $nome;
	protected $data_inicio;
	protected $data_termino;
	protected $plano_diario;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'nome' => 'string',
		'data_inicio' => 'date',
		'data_termino' => 'date',
		'plano_diario' => 'int',
		'status' => 'string'
	];

}
