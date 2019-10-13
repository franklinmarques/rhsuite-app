<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Papd_atendimentos_model extends MY_Model
{
	protected static $table = 'papd_atendimentos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_paciente' => 'required|is_natural_no_zero|max_length[11]',
		'id_atividade' => 'required|is_natural_no_zero|max_length[11]',
		'data_atendimento' => 'required|valid_datetime'
	];

}
