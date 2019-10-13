<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades_scheduler_meses_model extends MY_Model
{
	protected static $table = 'atividades_scheduler_meses';

	protected static $primaryKey = 'id_atividade_scheduler';

	protected static $autoIncrement = false;

	protected $validationRules = [
		'id_atividade_scheduler' => 'required|is_natural_no_zero|max_length[11]',
		'janeiro' => 'numeric|less_than_equal_to[1]',
		'fevereiro' => 'numeric|less_than_equal_to[1]',
		'marco' => 'is_natural|less_than_equal_to[1]',
		'abril' => 'is_natural|less_than_equal_to[1]',
		'maio' => 'is_natural|less_than_equal_to[1]',
		'junho' => 'is_natural|less_than_equal_to[1]',
		'julho' => 'is_natural|less_than_equal_to[1]',
		'agosto' => 'is_natural|less_than_equal_to[1]',
		'setembro' => 'is_natural|less_than_equal_to[1]',
		'outubro' => 'is_natural|less_than_equal_to[1]',
		'novembro' => 'is_natural|less_than_equal_to[1]',
		'dezembro' => 'is_natural|less_than_equal_to[1]'
	];

}
