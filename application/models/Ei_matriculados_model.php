<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_matriculados_model extends MY_Model
{
	protected static $table = 'ei_matriculados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao_escola' => 'required|is_natural_no_zero|max_length[11]',
		'id_os_aluno' => 'is_natural_no_zero|max_length[11]',
		'id_aluno' => 'is_natural_no_zero|max_length[11]',
		'aluno' => 'required|max_length[255]',
		'id_aluno_curso' => 'integer|max_length[11]',
		'id_curso' => 'integer|max_length[11]',
		'curso' => 'max_length[255]',
		'id_disciplina' => 'integer|max_length[11]',
		'disciplina' => 'max_length[255]',
		'hipotese_diagnostica' => 'max_length[255]',
		'modulo' => 'required|max_length[20]',
		'status' => 'required|exact_length[1]',
		'data_inicio' => 'valid_date',
		'data_termino' => 'valid_date',
		'data_recesso' => 'valid_date',
		'media_semestral' => 'decimal|max_length[4]'
	];

}
