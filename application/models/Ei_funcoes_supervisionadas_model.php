<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_funcoes_supervisionadas_model extends MY_Model
{
	protected static $table = 'ei_funcoes_supervisionadas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisor' => 'required|is_natural_no_zero|max_length[11]',
		'cargo' => 'required|is_natural_no_zero|max_length[11]',
		'funcao' => 'required|is_natural_no_zero|max_length[11]'
	];

}
