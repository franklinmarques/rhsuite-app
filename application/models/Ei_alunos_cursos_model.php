<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_alunos_cursos_model extends MY_Model
{
	protected static $table = 'ei_alunos_cursos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_aluno' => 'required|is_natural_no_zero|max_length[11]',
		'id_curso' => 'required|is_natural_no_zero|max_length[11]',
		'id_escola' => 'required|is_natural_no_zero|max_length[11]',
		'qtde_semestre' => 'required|integer|max_length[2]',
		'semestre_inicial' => 'required|max_length[6]',
		'semestre_final' => 'max_length[6]',
		'nota_geral' => 'decimal|max_length[4]',
		'status_ativo' => 'integer|max_length[1]'
	];

}
