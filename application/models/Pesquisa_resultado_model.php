<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_resultado_model extends MY_Model
{
	protected static $table = 'pesquisa_resultado';

	protected static $createdField = 'data_avaliacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliador' => 'required|is_natural_no_zero|max_length[11]',
		'id_pergunta' => 'required|is_natural_no_zero|max_length[11]',
		'id_alternativa' => 'is_natural_no_zero|max_length[11]',
		'valor' => 'integer|max_length[11]',
		'resposta' => 'max_length[4294967295]',
		'data_avaliacao' => 'required|valid_datetime'
	];

}
