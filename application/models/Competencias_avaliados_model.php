<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_avaliados_model extends MY_Model
{
	protected static $table = 'competencias_avaliados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_competencia' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]'
	];

}
