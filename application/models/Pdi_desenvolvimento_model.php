<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pdi_desenvolvimento_model extends MY_Model
{
	protected static $table = 'pdi_desenvolvimento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_pdi' => 'required|is_natural_no_zero|max_length[11]',
		'competencia' => 'required|max_length[45]',
		'descricao' => 'required|max_length[4294967295]',
		'expectativa' => 'required|max_length[4294967295]',
		'resultado' => 'required|max_length[4294967295]',
		'data_inicio' => 'required|valid_datetime',
		'data_termino' => 'required|valid_datetime',
		'status' => 'exact_length[1]'
	];

}
