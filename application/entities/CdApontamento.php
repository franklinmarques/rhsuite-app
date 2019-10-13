<?php

include_once APPPATH . 'entities/Entity.php';

class CdApontamento extends Entity
{
	protected $id;
	protected $id_alocado;
	protected $data;
	protected $data_afastamento;
	protected $id_cuidador_sub;
	protected $status;
	protected $qtde_dias;
	protected $apontamento_asc;
	protected $apontamento_desc;
	protected $saldo;
	protected $observacoes;

	protected $casts = [
		'id' => 'int',
		'id_alocado' => 'int',
		'data' => 'date',
		'data_afastamento' => '?date',
		'id_cuidador_sub' => '?int',
		'status' => 'string',
		'qtde_dias' => '?int',
		'apontamento_asc' => '?time',
		'apontamento_desc' => '?time',
		'saldo' => '?int',
		'observacoes' => '?string'
	];

}
