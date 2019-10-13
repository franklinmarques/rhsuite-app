<?php

include_once APPPATH . 'entities/Entity.php';

class EiAlunosTurmas extends Entity
{
	protected $id;
	protected $id_semestre;
	protected $id_disciplina;
	protected $id_cuidador;
	protected $dia_semana;
	protected $hora_inicio;
	protected $hora_termino;
	protected $periodo;
	protected $nota;

	protected $casts = [
		'id' => 'int',
		'id_semestre' => 'int',
		'id_disciplina' => 'int',
		'id_cuidador' => '?int',
		'dia_semana' => '?int',
		'hora_inicio' => '?time',
		'hora_termino' => '?time',
		'periodo' => '?string',
		'nota' => '?float'
	];

}
