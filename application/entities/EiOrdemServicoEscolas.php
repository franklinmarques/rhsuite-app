<?php

include_once APPPATH . 'entities/Entity.php';

class EiOrdemServicoEscolas extends Entity
{
	protected $id;
	protected $id_ordem_servico;
	protected $id_escola;

	protected $casts = [
		'id' => 'int',
		'id_ordem_servico' => 'int',
		'id_escola' => 'int'
	];

}
