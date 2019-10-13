<?php

include_once APPPATH . 'entities/Entity.php';

class EiAlocacao extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $depto;
	protected $id_diretoria;
	protected $diretoria;
	protected $id_supervisor;
	protected $supervisor;
	protected $municipio;
	protected $coordenador;
	protected $ano;
	protected $semestre;
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
	protected $observacoes_mes1;
	protected $observacoes_mes2;
	protected $observacoes_mes3;
	protected $observacoes_mes4;
	protected $observacoes_mes5;
	protected $observacoes_mes6;
	protected $observacoes_mes7;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'depto' => 'string',
		'id_diretoria' => 'int',
		'diretoria' => 'string',
		'id_supervisor' => 'int',
		'supervisor' => 'string',
		'municipio' => 'string',
		'coordenador' => 'string',
		'ano' => 'int',
		'semestre' => 'int',
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
		'saldo_acumulado_mes7' => '?string',
		'observacoes_mes1' => '?string',
		'observacoes_mes2' => '?string',
		'observacoes_mes3' => '?string',
		'observacoes_mes4' => '?string',
		'observacoes_mes5' => '?string',
		'observacoes_mes6' => '?string',
		'observacoes_mes7' => '?string'
	];

}
