<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_contas_itens_model extends MY_Model
{
	protected static $table = 'facilities_contas_itens';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_unidade' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'medidor' => 'required|max_length[65535]',
		'endereco' => 'required|max_length[65535]'
	];

}
