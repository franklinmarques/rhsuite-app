<?php

include_once APPPATH . 'entities/Entity.php';

class RecrutamentoTestes extends Entity
{
	protected $id;
	protected $id_candidato;
	protected $id_modelo;
	protected $data_inicio;
	protected $data_termino;
	protected $minutos_duracao;
	protected $aleatorizacao;
	protected $data_acesso;
	protected $data_envio;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_candidato' => 'int',
		'id_modelo' => 'int',
		'data_inicio' => 'datetime',
		'data_termino' => 'datetime',
		'minutos_duracao' => '?int',
		'aleatorizacao' => '?string',
		'data_acesso' => '?datetime',
		'data_envio' => '?datetime',
		'status' => '?string'
	];

}
