<?php

include_once APPPATH . 'entities/Entity.php';

class PapdZarit extends Entity
{
	protected $id;
	protected $id_paciente;
	protected $avaliador;
	protected $pessoa_pesquisada;
	protected $data_avaliacao;
	protected $zarit;
	protected $observacoes;
	protected $assistencia_excessiva;
	protected $tempo_desperdicado;
	protected $estresse_cotidiano;
	protected $constrangimento_alheio;
	protected $influencia_negativa;
	protected $futuro_receoso;
	protected $dependencia;
	protected $impacto_saude;
	protected $perda_privacidade;
	protected $perda_vida_social;
	protected $dependencia_exclusiva;
	protected $tempo_desgaste;
	protected $perda_controle;
	protected $duvida_prestatividade;
	protected $expectativa_qualidade;
	protected $sobrecarga;

	protected $casts = [
		'id' => 'int',
		'id_paciente' => 'int',
		'avaliador' => 'string',
		'pessoa_pesquisada' => '?string',
		'data_avaliacao' => 'date',
		'zarit' => '?int',
		'observacoes' => '?string',
		'assistencia_excessiva' => '?int',
		'tempo_desperdicado' => '?int',
		'estresse_cotidiano' => '?int',
		'constrangimento_alheio' => '?int',
		'influencia_negativa' => '?int',
		'futuro_receoso' => '?int',
		'dependencia' => '?int',
		'impacto_saude' => '?int',
		'perda_privacidade' => '?int',
		'perda_vida_social' => '?int',
		'dependencia_exclusiva' => '?int',
		'tempo_desgaste' => '?int',
		'perda_controle' => '?int',
		'duvida_prestatividade' => '?int',
		'expectativa_qualidade' => '?int',
		'sobrecarga' => '?int'
	];

}
