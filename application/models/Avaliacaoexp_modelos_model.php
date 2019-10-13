<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_modelos_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_modelos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[45]',
		'id_usuario_EMPRESA' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'required|exact_length[1]',
		'observacao' => 'max_length[4294967295]'
	];

}
