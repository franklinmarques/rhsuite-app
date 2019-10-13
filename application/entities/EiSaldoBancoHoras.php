<?php

include_once APPPATH . 'entities/Entity.php';

class EiSaldoBancoHoras extends Entity
{
	protected $id;
	protected $id_supervisao;
	protected $saldo_mes1;
	protected $saldo_mes2;
	protected $saldo_mes3;
	protected $saldo_mes4;
	protected $saldo_mes5;
	protected $saldo_mes6;
	protected $saldo_mes7;
	protected $saldo_acumulado_mes1;
	protected $saldo_acumulado_mes2;
	protected $saldo_acumulado_mes3;
	protected $saldo_acumulado_mes4;
	protected $saldo_acumulado_mes5;
	protected $saldo_acumulado_mes6;
	protected $saldo_acumulado_mes7;

	protected $casts = [
		'id' => 'int',
		'id_supervisao' => 'int',
		'saldo_mes1' => '?string',
		'saldo_mes2' => '?string',
		'saldo_mes3' => '?string',
		'saldo_mes4' => '?string',
		'saldo_mes5' => '?string',
		'saldo_mes6' => '?string',
		'saldo_mes7' => '?string',
		'saldo_acumulado_mes1' => '?string',
		'saldo_acumulado_mes2' => '?string',
		'saldo_acumulado_mes3' => '?string',
		'saldo_acumulado_mes4' => '?string',
		'saldo_acumulado_mes5' => '?string',
		'saldo_acumulado_mes6' => '?string',
		'saldo_acumulado_mes7' => '?string'
	];

}
