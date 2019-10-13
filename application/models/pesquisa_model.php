<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_model extends MY_Model
{
	protected static $table = 'pesquisa';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'data_inicio' => 'required|valid_date',
		'data_termino' => 'required|valid_date|after_or_equal_date[data_inicio]'
	];

}
