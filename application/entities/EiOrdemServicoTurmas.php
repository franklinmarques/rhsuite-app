<?php

include_once APPPATH . 'entities/Entity.php';

class EiOrdemServicoTurmas extends Entity
{
	protected $id_os_aluno;
	protected $id_os_horario;

	protected $casts = [
		'id_os_aluno' => 'int',
		'id_os_horario' => 'int'
	];

}
