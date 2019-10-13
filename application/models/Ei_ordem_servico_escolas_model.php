<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_ordem_servico_escolas_model extends MY_Model
{
	protected static $table = 'ei_ordem_servico_escolas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_ordem_servico' => 'required|is_natural_no_zero|max_length[11]',
		'id_escola' => 'required|is_natural_no_zero|max_length[11]'
	];

}
