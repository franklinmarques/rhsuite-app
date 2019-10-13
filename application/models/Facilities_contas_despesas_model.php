<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_contas_despesas_model extends MY_Model
{
	protected static $table = 'facilities_contas_despesas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_item' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'valor' => 'required|decimal|max_length[11]',
		'data_vencimento' => 'required|valid_date',
		'mes' => 'required|integer|max_length[2]',
		'ano' => 'required|is_natural_no_zero|max_length[4]'
	];

}
