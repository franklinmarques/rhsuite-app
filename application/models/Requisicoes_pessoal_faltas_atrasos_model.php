<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_faltas_atrasos_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_faltas_atrasos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'required|is_natural_no_zero|max_length[11]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'mes' => 'required|integer|max_length[2]',
		'total_faltas' => 'required|integer|max_length[11]',
		'total_atrasos' => 'required|integer|max_length[11]',
		'tempo_total_atraso' => 'valid_time'
	];

}
