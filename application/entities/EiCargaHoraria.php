<?php

include_once APPPATH . 'entities/Entity.php';

class EiCargaHoraria extends Entity
{
	protected $id;
	protected $id_supervisao;
	protected $data;
	protected $horario_entrada;
	protected $horario_saida;
	protected $horario_entrada_1;
	protected $horario_saida_1;
	protected $total;
	protected $carga_horaria;
	protected $saldo_dia;
	protected $observacoes;

	protected $casts = [
		'id' => 'int',
		'id_supervisao' => 'int',
		'data' => '?date',
		'horario_entrada' => '?time',
		'horario_saida' => '?time',
		'horario_entrada_1' => '?time',
		'horario_saida_1' => '?time',
		'total' => '?time',
		'carga_horaria' => '?time',
		'saldo_dia' => '?time',
		'observacoes' => '?string'
	];

}
