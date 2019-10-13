<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_modelos_model extends MY_Model
{
	protected static $table = 'competencias_modelos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'tipo' => 'required|exact_length[1]'
	];

	protected static $tipo = ['T' => 'Técnica', 'C' => 'Comportamental'];

}
