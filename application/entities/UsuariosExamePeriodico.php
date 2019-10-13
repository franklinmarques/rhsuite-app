<?php

include_once APPPATH . 'entities/Entity.php';

class UsuariosExamePeriodico extends Entity
{
	protected $id;
	protected $id_usuario;
	protected $data_programada;
	protected $data_realizacao;
	protected $data_entrega;
	protected $data_entrega_copia;
	protected $local_exame;
	protected $observacoes;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'data_programada' => 'date',
		'data_realizacao' => '?date',
		'data_entrega' => '?date',
		'data_entrega_copia' => '?date',
		'local_exame' => '?string',
		'observacoes' => '?string'
	];

}
