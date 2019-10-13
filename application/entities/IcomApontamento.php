<?php

include_once APPPATH . 'entities/Entity.php';

class IcomApontamento extends Entity
{
	protected $id;
	protected $id_alocado;
	protected $data;
	protected $tipo_evento;
	protected $horario_entrada;
	protected $horario_intervalo;
	protected $horario_retorno;
	protected $horario_saida;
	protected $hora_extra;
	protected $desconto_folha;
	protected $banco_horas;
	protected $saldo_banco_horas;
	protected $observacoes;

	protected $casts = [
		'id' => 'int',
		'id_alocado' => 'int',
		'data' => 'date',
		'tipo_evento' => 'string',
		'horario_entrada' => '?time',
		'horario_intervalo' => '?time',
		'horario_retorno' => '?time',
		'horario_saida' => '?time',
		'hora_extra' => '?time',
		'desconto_folha' => '?time',
		'banco_horas' => '?time',
		'saldo_banco_horas' => '?time',
		'observacoes' => '?string'
	];

}
