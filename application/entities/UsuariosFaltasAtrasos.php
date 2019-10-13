<?php

include_once APPPATH . 'entities/Entity.php';

class UsuariosFaltasAtrasos extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_usuario;
	protected $id_colaborador;
	protected $id_depto;
	protected $id_area;
	protected $id_setor;
	protected $data;
	protected $falta;
	protected $horas_atraso;
	protected $id_colaborador_sub;
	protected $status;
	protected $observacoes;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_usuario' => 'int',
		'id_colaborador' => '?int',
		'id_depto' => 'int',
		'id_area' => 'int',
		'id_setor' => 'int',
		'data' => 'date',
		'falta' => '?int',
		'horas_atraso' => '?time',
		'id_colaborador_sub' => '?int',
		'status' => 'string',
		'observacoes' => '?string'
	];

}
