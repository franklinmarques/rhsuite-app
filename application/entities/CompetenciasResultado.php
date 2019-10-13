<?php

include_once APPPATH . 'entities/Entity.php';

class CompetenciasResultado extends Entity
{
	protected $id;
	protected $id_avaliador;
	protected $cargo_dimensao;
	protected $nivel;
	protected $atitude;

	protected $casts = [
		'id' => 'int',
		'id_avaliador' => 'int',
		'cargo_dimensao' => 'int',
		'nivel' => '?int',
		'atitude' => '?int'
	];

}
