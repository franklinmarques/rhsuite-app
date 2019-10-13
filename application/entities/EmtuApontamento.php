<?php

include_once APPPATH . 'entities/Entity.php';

class EmtuApontamento extends Entity
{
	protected $id;
	protected $id_alocado;
	protected $data;
	protected $horario_entrada;
	protected $horario_intervalo;
	protected $horario_retorno;
	protected $horario_saida;
	protected $qtde_dias;
	protected $hora_atraso;
	protected $hora_extra;
	protected $desconto_folha;
	protected $saldo_banco_horas;
	protected $hora_glosa;
	protected $observacoes;
	protected $status;
	protected $id_alocado_bck;

	protected $casts = [
		'id' => 'int',
		'id_alocado' => 'int',
		'data' => 'date',
		'horario_entrada' => '?datetime',
		'horario_intervalo' => '?datetime',
		'horario_retorno' => '?datetime',
		'horario_saida' => '?datetime',
		'qtde_dias' => '?int',
		'hora_atraso' => '?time',
		'hora_extra' => '?time',
		'desconto_folha' => '?time',
		'saldo_banco_horas' => '?time',
		'hora_glosa' => '?time',
		'observacoes' => '?string',
		'status' => 'string',
		'id_alocado_bck' => '?int'
	];

}
