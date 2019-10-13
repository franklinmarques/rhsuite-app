<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_ordem_servico_alunos_model extends MY_Model
{
	protected static $table = 'ei_ordem_servico_alunos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_ordem_servico_escola' => 'required|is_natural_no_zero|max_length[11]',
		'id_aluno' => 'required|is_natural_no_zero|max_length[11]',
		'id_aluno_curso' => 'required|is_natural_no_zero|max_length[11]',
		'data_inicio' => 'required|valid_date',
		'data_termino' => 'required|valid_date',
		'modulo' => 'required|max_length[20]',
		'nota' => 'decimal|max_length[4]'
	];

}
