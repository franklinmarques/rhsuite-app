<?php

include_once APPPATH . 'entities/Entity.php';

class EiOrdemServicoAlunos extends Entity
{
	protected $id;
	protected $id_ordem_servico_escola;
	protected $id_aluno;
	protected $id_aluno_curso;
	protected $data_inicio;
	protected $data_termino;
	protected $modulo;
	protected $nota;

	protected $casts = [
		'id' => 'int',
		'id_ordem_servico_escola' => 'int',
		'id_aluno' => 'int',
		'id_aluno_curso' => 'int',
		'data_inicio' => 'date',
		'data_termino' => 'date',
		'modulo' => 'string',
		'nota' => '?float'
	];

}
