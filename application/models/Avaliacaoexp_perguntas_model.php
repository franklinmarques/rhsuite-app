<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_perguntas_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_perguntas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'pergunta' => 'required|max_length[255]',
		'tipo' => 'required|exact_length[1]'
	];

}
