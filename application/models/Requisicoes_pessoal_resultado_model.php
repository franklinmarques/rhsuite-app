<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_resultado_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_resultado';

	protected static $createdField = 'data_avaliacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_teste' => 'required|is_natural_no_zero|max_length[11]',
		'id_pergunta' => 'required|is_natural_no_zero|max_length[11]',
		'peso_max' => 'integer|max_length[3]',
		'id_alternativa' => 'is_natural_no_zero|max_length[11]',
		'valor' => 'integer|max_length[11]',
		'resposta' => 'max_length[4294967295]',
		'nota' => 'integer|max_length[2]',
		'data_avaliacao' => 'valid_datetime'
	];

}
