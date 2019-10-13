<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_lifo_comportamentos_model extends MY_Model
{
	protected static $table = 'pesquisa_lifo_comportamentos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_estilo' => 'required|is_natural_no_zero|max_length[11]',
		'situacao_comportamental' => 'required|exact_length[1]',
		'nome' => 'required|max_length[100]'
	];

}
