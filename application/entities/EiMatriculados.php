<?php

include_once APPPATH . 'entities/Entity.php';

class EiMatriculados extends Entity
{
	protected $id;
	protected $id_alocacao_escola;
	protected $id_os_aluno;
	protected $id_aluno;
	protected $aluno;
	protected $id_aluno_curso;
	protected $id_curso;
	protected $curso;
	protected $id_disciplina;
	protected $disciplina;
	protected $hipotese_diagnostica;
	protected $modulo;
	protected $status;
	protected $data_inicio;
	protected $data_termino;
	protected $data_recesso;
	protected $media_semestral;

	protected $casts = [
		'id' => 'int',
		'id_alocacao_escola' => 'int',
		'id_os_aluno' => '?int',
		'id_aluno' => '?int',
		'aluno' => 'string',
		'id_aluno_curso' => '?int',
		'id_curso' => '?int',
		'curso' => '?string',
		'id_disciplina' => '?int',
		'disciplina' => '?string',
		'hipotese_diagnostica' => '?string',
		'modulo' => 'string',
		'status' => 'string',
		'data_inicio' => '?date',
		'data_termino' => '?date',
		'data_recesso' => '?date',
		'media_semestral' => '?float'
	];

}
