<?php

include_once APPPATH . 'entities/Entity.php';

class EadQuestoes extends Entity
{
	protected $id;
	protected $nome;
	protected $id_pagina;
	protected $tipo;
	protected $conteudo;
	protected $feedback_correta;
	protected $feedback_incorreta;
	protected $observacoes;
	protected $aleatorizacao;
	protected $id_biblioteca;
	protected $id_copia;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_pagina' => 'int',
		'tipo' => '?string',
		'conteudo' => '?string',
		'feedback_correta' => '?string',
		'feedback_incorreta' => '?string',
		'observacoes' => '?string',
		'aleatorizacao' => '?string',
		'id_biblioteca' => '?int',
		'id_copia' => '?int'
	];

}
