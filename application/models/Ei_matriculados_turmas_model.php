<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_matriculados_turmas_model extends MY_Model
{
	protected static $table = 'ei_matriculados_turmas';

	protected static $primaryKey = 'id_matriculado';

	protected static $autoIncrement = false;

	protected $validationRules = [
		'id_matriculado' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocado_horario' => 'required|is_natural_no_zero|max_length[11]'
	];

}
