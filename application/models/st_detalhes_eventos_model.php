<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_detalhes_eventos_model extends MY_Model
{
	protected static $table = 'alocacao_eventos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'codigo' => 'required|max_length[30]',
		'nome' => 'required|max_length[255]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]'
	];

}
