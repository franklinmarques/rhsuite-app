<?php

include_once APPPATH . 'entities/Entity.php';

class EiCoordenacao extends Entity
{
	protected $id;
	protected $id_usuario;
	protected $depto;
	protected $area;
	protected $setor;
	protected $ano;
	protected $semestre;
	protected $carga_horaria;
	protected $saldo_acumulado_horas;
	protected $is_coordenador;
	protected $is_supervisor;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'depto' => 'int',
		'area' => 'int',
		'setor' => 'int',
		'ano' => 'int',
		'semestre' => 'int',
		'carga_horaria' => '?time',
		'saldo_acumulado_horas' => '?string',
		'is_coordenador' => '?int',
		'is_supervisor' => '?int'
	];

}
