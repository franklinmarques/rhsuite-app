<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_avaliados_model extends MY_Model
{
	protected static $table = 'pesquisa_avaliados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_pesquisa' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliado' => 'required|is_natural_no_zero|max_length[11]'
	];

}
