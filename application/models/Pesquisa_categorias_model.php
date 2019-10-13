<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_categorias_model extends MY_Model
{
	protected static $table = 'pesquisa_categorias';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'categoria' => 'required|max_length[50]'
	];

}
