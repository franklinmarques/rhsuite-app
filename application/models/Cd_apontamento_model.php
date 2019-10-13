<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_apontamento_model extends MY_Model
{
	protected static $table = 'cd_apontamento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocado' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'data_afastamento' => 'valid_date',
		'id_cuidador_sub' => 'is_natural_no_zero|max_length[11]',
		'status' => 'required|exact_length[2]',
		'qtde_dias' => 'integer|max_length[2]',
		'apontamento_asc' => 'valid_time',
		'apontamento_desc' => 'valid_time',
		'saldo' => 'integer|max_length[11]',
		'observacoes' => 'max_length[4294967295]'
	];

}
