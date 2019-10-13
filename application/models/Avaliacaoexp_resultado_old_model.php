<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_resultado_old_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_resultado_old';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliador' => 'required|integer|max_length[11]',
		'id_pergunta' => 'required|integer|max_length[11]',
		'id_alternativa' => 'integer|max_length[11]',
		'resposta' => 'max_length[4294967295]',
		'data_avaliacao' => 'required|valid_datetime'
	];

}
