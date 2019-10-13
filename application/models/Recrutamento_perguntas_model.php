<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_perguntas_model extends MY_Model
{
	protected static $table = 'recrutamento_perguntas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'pergunta' => 'required|max_length[4294967295]',
		'tipo_resposta' => 'required|exact_length[1]',
		'tipo_eneagrama' => 'integer|max_length[1]',
		'id_competencia' => 'is_natural_no_zero|max_length[11]',
		'competencia' => 'max_length[255]',
		'justificativa' => 'integer|max_length[1]',
		'valor_min' => 'integer|max_length[11]',
		'valor_max' => 'integer|max_length[11]'
	];

}
