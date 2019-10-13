<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_ordem_servico_turmas_model extends MY_Model
{
	protected static $table = 'ei_ordem_servico_turmas';

	protected static $primaryKey = 'id_os_aluno';

	protected static $autoIncrement = false;

	protected $validationRules = [
		'id_os_aluno' => 'required|is_natural_no_zero|max_length[11]',
		'id_os_horario' => 'required|is_natural_no_zero|max_length[11]'
	];

}
