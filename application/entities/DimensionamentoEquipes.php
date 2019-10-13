<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoEquipes extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_depto;
	protected $id_area;
	protected $id_setor;
	protected $nome;
	protected $total_componentes;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_depto' => '?int',
		'id_area' => '?int',
		'id_setor' => '?int',
		'nome' => 'string',
		'total_componentes' => 'int'
	];

}
