<?php

include_once APPPATH . 'entities/Entity.php';

class PesquisaQuatiEstilos extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $nome;
	protected $laudo_comportamental_padrao;
	protected $perfil_preponderante;
	protected $atitude_primaria;
	protected $atitude_secundaria;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'nome' => 'string',
		'laudo_comportamental_padrao' => '?string',
		'perfil_preponderante' => 'string',
		'atitude_primaria' => 'string',
		'atitude_secundaria' => 'string'
	];

}
