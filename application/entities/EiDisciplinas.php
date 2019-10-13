<?php

include_once APPPATH . 'entities/Entity.php';

class EiDisciplinas extends Entity
{
	protected $id;
	protected $id_curso;
	protected $nome;
	protected $qtde_semestres;

	protected $casts = [
		'id' => 'int',
		'id_curso' => 'int',
		'nome' => 'string',
		'qtde_semestres' => '?int'
	];

}
