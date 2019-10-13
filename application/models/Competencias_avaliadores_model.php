<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_avaliadores_model extends MY_Model
{
	protected static $table = 'competencias_avaliadores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliado' => 'required|is_natural_no_zero|max_length[11]'
	];

}
