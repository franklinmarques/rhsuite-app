<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pdi_model extends MY_Model
{
	protected static $table = 'pdi';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'usuario' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'descricao' => 'max_length[4294967295]',
		'data_inicio' => 'valid_datetime',
		'data_termino' => 'valid_datetime',
		'observacao' => 'max_length[4294967295]',
		'status' => 'exact_length[1]'
	];

}
