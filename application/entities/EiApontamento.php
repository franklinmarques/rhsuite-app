<?php

include_once APPPATH . 'entities/Entity.php';

class EiApontamento extends Entity
{
	protected $id;
	protected $id_alocado;
	protected $data;
	protected $periodo;
	protected $horario_inicio;
	protected $status;
	protected $id_usuario;
	protected $id_alocado_sub1;
	protected $id_alocado_sub2;
	protected $desconto;
	protected $desconto_sub1;
	protected $desconto_sub2;
	protected $ocorrencia_cuidador;
	protected $ocorrencia_aluno;
	protected $ocorrencia_professor;

	protected $casts = [
		'id' => 'int',
		'id_alocado' => 'int',
		'data' => 'date',
		'periodo' => '?int',
		'horario_inicio' => '?time',
		'status' => 'string',
		'id_usuario' => '?int',
		'id_alocado_sub1' => '?int',
		'id_alocado_sub2' => '?int',
		'desconto' => '?time',
		'desconto_sub1' => '?time',
		'desconto_sub2' => '?time',
		'ocorrencia_cuidador' => '?string',
		'ocorrencia_aluno' => '?string',
		'ocorrencia_professor' => '?string'
	];

}
