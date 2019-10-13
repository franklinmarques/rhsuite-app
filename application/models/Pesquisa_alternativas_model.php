<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_alternativas_model extends MY_Model
{
	protected static $table = 'pesquisa_alternativas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'id_pergunta' => 'is_natural_no_zero|max_length[11]',
		'alternativa' => 'required|max_length[4294967295]',
		'peso' => 'required|integer|max_length[2]'
	];

}
