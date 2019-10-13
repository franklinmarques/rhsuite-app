<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_matriculados_model extends MY_Model
{
	protected static $table = 'cd_matriculados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_aluno' => 'integer|max_length[11]',
		'aluno' => 'required|max_length[255]',
		'escola' => 'required|max_length[255]',
		'supervisor' => 'required|max_length[255]',
		'hipotese_diagnostica' => 'required|max_length[255]',
		'turno' => 'required|exact_length[1]',
		'status' => 'required|exact_length[1]',
		'dia_inicial' => 'integer|max_length[2]',
		'dia_limite' => 'integer|max_length[2]'
	];

}
