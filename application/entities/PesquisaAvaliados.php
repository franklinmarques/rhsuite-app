<?php

include_once APPPATH . 'entities/Entity.php';

class PesquisaAvaliados extends Entity
{
	protected $id;
	protected $id_pesquisa;
	protected $id_avaliado;

	protected $casts = [
		'id' => 'int',
		'id_pesquisa' => 'int',
		'id_avaliado' => 'int'
	];

}
