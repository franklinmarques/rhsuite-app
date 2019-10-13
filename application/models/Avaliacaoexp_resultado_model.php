<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_resultado_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_resultado';

	protected static $createdField = 'data_avaliacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliador' => 'required|is_natural_no_zero|max_length[11]',
		'id_pergunta' => 'required|is_natural_no_zero|max_length[11]',
		'id_alternativa' => 'is_natural_no_zero|max_length[11]',
		'resposta' => 'max_length[4294967295]',
		'data_avaliacao' => 'required|valid_datetime'
	];

}
