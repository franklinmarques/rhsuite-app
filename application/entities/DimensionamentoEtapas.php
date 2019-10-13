<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoEtapas extends Entity
{
	protected $id;
	protected $id_atividade;
	protected $nome;
	protected $grau_complexidade;
	protected $tamanho_item;
	protected $peso_item;

	protected $casts = [
		'id' => 'int',
		'id_atividade' => 'int',
		'nome' => 'string',
		'grau_complexidade' => '?int',
		'tamanho_item' => '?int',
		'peso_item' => '?float'
	];

}
