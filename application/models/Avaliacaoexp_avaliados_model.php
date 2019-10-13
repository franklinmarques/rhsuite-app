<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_avaliados_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_avaliados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliado' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisor' => 'is_natural_no_zero|max_length[11]',
		'data_atividades' => 'required|valid_datetime',
		'nota_corte' => 'required|integer|max_length[2]',
		'observacoes' => 'max_length[65535]',
		'id_avaliacao' => 'is_natural_no_zero|max_length[11]'
	];

}
