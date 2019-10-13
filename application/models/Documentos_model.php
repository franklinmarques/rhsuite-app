<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documentos_model extends MY_Model
{
	protected static $table = 'documentos';

	protected static $createdField = 'datacadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'datacadastro' => 'required|valid_datetime',
		'colaborador' => 'integer|max_length[11]',
		'tipo' => 'integer|max_length[11]',
		'descricao' => 'required|max_length[200]',
		'arquivo' => 'max_length[65535]',
		'usuario' => 'required|integer|max_length[11]'
	];

}
