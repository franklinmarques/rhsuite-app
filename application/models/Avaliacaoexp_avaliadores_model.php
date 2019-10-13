<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_avaliadores_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_avaliadores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliado' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliador' => 'required|is_natural_no_zero|max_length[11]',
		'data_avaliacao' => 'required|valid_date',
		'id_evento' => 'integer|max_length[11]'
	];

}
