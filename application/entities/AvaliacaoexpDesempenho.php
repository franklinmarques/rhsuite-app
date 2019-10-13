<?php

include_once APPPATH . 'entities/Entity.php';

class AvaliacaoexpDesempenho extends Entity
{
	protected $id_avaliador;
	protected $pontos_fortes;
	protected $pontos_fracos;
	protected $observacoes;
	protected $data;

	protected $casts = [
		'id_avaliador' => 'int',
		'pontos_fortes' => '?string',
		'pontos_fracos' => '?string',
		'observacoes' => '?string',
		'data' => '?datetime'
	];

}
