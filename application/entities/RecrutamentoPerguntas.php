<?php

include_once APPPATH . 'entities/Entity.php';

class RecrutamentoPerguntas extends Entity
{
	protected $id;
	protected $id_modelo;
	protected $pergunta;
	protected $tipo_resposta;
	protected $tipo_eneagrama;
	protected $id_competencia;
	protected $competencia;
	protected $justificativa;
	protected $valor_min;
	protected $valor_max;

	protected $casts = [
		'id' => 'int',
		'id_modelo' => 'int',
		'pergunta' => 'string',
		'tipo_resposta' => 'string',
		'tipo_eneagrama' => '?int',
		'id_competencia' => '?int',
		'competencia' => '?string',
		'justificativa' => '?int',
		'valor_min' => '?int',
		'valor_max' => '?int'
	];

}
