<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_dimensao_model extends MY_Model
{
	protected static $table = 'competencias_dimensao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]'
	];

}
