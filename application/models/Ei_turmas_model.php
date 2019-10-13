<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_turmas_model extends MY_Model
{
	protected static $table = 'ei_turmas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_semestre' => 'required|integer|max_length[11]',
		'id_disciplina' => 'required|integer|max_length[11]',
		'id_cuidador' => 'integer|max_length[11]',
		'dia_semana' => 'integer|max_length[1]',
		'hora_inicio' => 'valid_time',
		'hora_termino' => 'valid_time',
		'periodo' => 'exact_length[1]',
		'nota' => 'decimal|max_length[4]'
	];

}
