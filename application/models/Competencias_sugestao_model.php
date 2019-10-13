<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_sugestao_model extends MY_Model
{
	protected static $table = 'competencias_sugestao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[200]',
		'tipo' => 'required|integer|max_length[11]'
	];

	protected static $tipo = ['1' => 'Técnica', '2' => 'Comportamental'];

}
