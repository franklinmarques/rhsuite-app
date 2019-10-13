<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tipo_documento_model extends MY_Model
{
	protected static $table = 'tipodocumento';

	protected static $createdField = 'datacadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'datacadastro' => 'required|valid_datetime',
		'descricao' => 'required|max_length[200]',
		'categoria' => 'integer|max_length[11]',
		'usuario' => 'required|integer|max_length[11]'
	];

}
