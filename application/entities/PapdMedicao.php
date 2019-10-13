<?php

include_once APPPATH . 'entities/Entity.php';

class PapdMedicao extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $ano;
	protected $mes;
	protected $total_pacientes_cadastrados;
	protected $total_pacientes_inativos;
	protected $total_pacientes_monitorados;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'ano' => 'int',
		'mes' => 'int',
		'total_pacientes_cadastrados' => 'int',
		'total_pacientes_inativos' => 'int',
		'total_pacientes_monitorados' => 'int'
	];

}
