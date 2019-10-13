<?php

include_once APPPATH . 'entities/Entity.php';

class EiCursos extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_diretoria;
	protected $nome;
	protected $qtde_semestres;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_diretoria' => 'int',
		'nome' => 'string',
		'qtde_semestres' => '?int'
	];

}
