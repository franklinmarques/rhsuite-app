<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Papd_zarit_model extends MY_Model
{
	protected static $table = 'papd_zarit';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_paciente' => 'required|is_natural_no_zero|max_length[11]',
		'avaliador' => 'required|max_length[255]',
		'pessoa_pesquisada' => 'max_length[255]',
		'data_avaliacao' => 'required|valid_date',
		'zarit' => 'integer|max_length[3]',
		'observacoes' => 'max_length[4294967295]',
		'assistencia_excessiva' => 'numeric|max_length[1]',
		'tempo_desperdicado' => 'numeric|max_length[1]',
		'estresse_cotidiano' => 'numeric|max_length[1]',
		'constrangimento_alheio' => 'numeric|max_length[1]',
		'influencia_negativa' => 'numeric|max_length[1]',
		'futuro_receoso' => 'numeric|max_length[1]',
		'dependencia' => 'numeric|max_length[1]',
		'impacto_saude' => 'numeric|max_length[1]',
		'perda_privacidade' => 'numeric|max_length[1]',
		'perda_vida_social' => 'numeric|max_length[1]',
		'dependencia_exclusiva' => 'numeric|max_length[1]',
		'tempo_desgaste' => 'numeric|max_length[1]',
		'perda_controle' => 'numeric|max_length[1]',
		'duvida_prestatividade' => 'numeric|max_length[1]',
		'expectativa_qualidade' => 'numeric|max_length[1]',
		'sobrecarga' => 'numeric|max_length[1]'
	];

}
