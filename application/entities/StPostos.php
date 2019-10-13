<?php

include_once APPPATH . 'entities/Entity.php';

class StPostos extends Entity
{
	protected $id;
	protected $id_usuario;
	protected $data;
	protected $depto;
	protected $area;
	protected $setor;
	protected $cargo;
	protected $funcao;
	protected $contrato;
	protected $total_dias_mensais;
	protected $total_horas_diarias;
	protected $matricula;
	protected $login;
	protected $horario_entrada;
	protected $horario_saida;
	protected $valor_posto;
	protected $valor_dia;
	protected $valor_hora;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'data' => 'date',
		'depto' => '?string',
		'area' => '?string',
		'setor' => '?string',
		'cargo' => '?string',
		'funcao' => '?string',
		'contrato' => '?string',
		'total_dias_mensais' => 'int',
		'total_horas_diarias' => 'int',
		'matricula' => '?string',
		'login' => '?string',
		'horario_entrada' => '?time',
		'horario_saida' => '?time',
		'valor_posto' => 'float',
		'valor_dia' => 'float',
		'valor_hora' => 'float'
	];

}
