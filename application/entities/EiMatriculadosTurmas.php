<?php

include_once APPPATH . 'entities/Entity.php';

class EiMatriculadosTurmas extends Entity
{
	protected $id_matriculado;
	protected $id_alocado_horario;

	protected $casts = [
		'id_matriculado' => 'int',
		'id_alocado_horario' => 'int'
	];

}
