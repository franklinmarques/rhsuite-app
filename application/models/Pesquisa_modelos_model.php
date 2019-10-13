<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_modelos_model extends MY_Model
{
	protected static $table = 'pesquisa_modelos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]',
		'id_usuario_EMPRESA' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'required|exact_length[1]',
		'observacoes' => 'max_length[4294967295]',
		'instrucoes' => 'max_length[4294967295]',
		'exclusao_bloqueada' => 'required|numeric|max_length[1]'
	];

}
