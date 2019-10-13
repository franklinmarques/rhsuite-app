<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoProgramas extends Entity
{
	protected $id;
	protected $id_job;
	protected $id_executor;
	protected $volume_trabalho;
	protected $qtde_horas_disponiveis;
	protected $tipo_valor;
	protected $tipo_mao_obra;
	protected $unidades;
	protected $mao_obra;
	protected $carga_horaria_necessaria;
	protected $horario_inicio_projetado;
	protected $horario_termino_projetado;
	protected $horario_inicio_real;
	protected $horario_termino_real;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_job' => 'int',
		'id_executor' => 'int',
		'volume_trabalho' => '?float',
		'qtde_horas_disponiveis' => '?float',
		'tipo_valor' => '?string',
		'tipo_mao_obra' => '?string',
		'unidades' => '?string',
		'mao_obra' => '?string',
		'carga_horaria_necessaria' => '?float',
		'horario_inicio_projetado' => '?time',
		'horario_termino_projetado' => '?time',
		'horario_inicio_real' => '?time',
		'horario_termino_real' => '?time',
		'status' => 'string'
	];

}
