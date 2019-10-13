<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades_scheduler_model extends MY_Model
{
	protected static $table = 'atividades_scheduler';

	protected static $createdField = 'data_cadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'is_natural_no_zero|max_length[11]',
		'atividade' => 'required|max_length[255]',
		'dia' => 'numeric|max_length[2]',
		'semana' => 'numeric|max_length[1]',
		'mes' => 'numeric|max_length[2]',
		'objetivos' => 'required|max_length[65535]',
		'data_cadastro' => 'required|valid_date',
		'data_limite' => 'max_length[255]',
		'envolvidos' => 'required|max_length[65535]',
		'observacoes' => 'max_length[65535]',
		'processo_roteiro' => 'max_length[65535]',
		'documento_1' => 'max_length[255]',
		'documento_2' => 'max_length[255]',
		'documento_3' => 'max_length[255]',
		'lembrar' => 'required|numeric|max_length[1]'
	];

}
