<?php

include_once APPPATH . 'entities/Entity.php';

class StApontamento extends Entity
{
	protected $id;
	protected $id_alocado;
	protected $data;
	protected $hora_entrada;
	protected $hora_intervalo;
	protected $hora_retorno;
	protected $hora_saida;
	protected $qtde_dias;
	protected $hora_atraso;
	protected $qtde_req;
	protected $qtde_rev;
	protected $apontamento_extra;
	protected $apontamento_desc;
	protected $apontamento_saldo;
	protected $apontamento_saldo_old;
	protected $hora_glosa;
	protected $detalhes;
	protected $observacoes;
	protected $status;
	protected $id_alocado_bck;

	protected $casts = [
		'id' => 'int',
		'id_alocado' => 'int',
		'data' => 'date',
		'hora_entrada' => '?datetime',
		'hora_intervalo' => '?datetime',
		'hora_retorno' => '?datetime',
		'hora_saida' => '?datetime',
		'qtde_dias' => '?int',
		'hora_atraso' => '?time',
		'qtde_req' => '?int',
		'qtde_rev' => '?int',
		'apontamento_extra' => '?time',
		'apontamento_desc' => '?time',
		'apontamento_saldo' => '?time',
		'apontamento_saldo_old' => '?time',
		'hora_glosa' => '?time',
		'detalhes' => '?int',
		'observacoes' => '?string',
		'status' => 'string',
		'id_alocado_bck' => '?int'
	];

}
