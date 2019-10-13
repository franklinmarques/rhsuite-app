<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoJobs extends Entity
{
	protected $id;
	protected $id_plano_trabalho;
	protected $nome;
	protected $data_inicio;
	protected $data_termino;
	protected $horario_inicio;
	protected $horario_termino;
	protected $plano_diario;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_plano_trabalho' => 'int',
		'nome' => 'string',
		'data_inicio' => 'date',
		'data_termino' => 'date',
		'horario_inicio' => '?time',
		'horario_termino' => '?time',
		'plano_diario' => 'int',
		'status' => 'string'
	];

}
