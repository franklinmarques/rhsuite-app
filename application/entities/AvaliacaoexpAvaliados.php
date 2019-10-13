<?php

include_once APPPATH . 'entities/Entity.php';

class AvaliacaoexpAvaliados extends Entity
{
	protected $id;
	protected $id_modelo;
	protected $id_avaliado;
	protected $id_supervisor;
	protected $data_atividades;
	protected $nota_corte;
	protected $observacoes;
	protected $id_avaliacao;

	protected $casts = [
		'id' => 'int',
		'id_modelo' => 'int',
		'id_avaliado' => 'int',
		'id_supervisor' => '?int',
		'data_atividades' => 'datetime',
		'nota_corte' => 'int',
		'observacoes' => '?string',
		'id_avaliacao' => '?int'
	];

}
