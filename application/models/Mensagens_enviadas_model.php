<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mensagens_enviadas_model extends MY_Model
{
	protected static $table = 'mensagensenviadas';

	protected static $createdField = 'datacadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'remetente' => 'required|integer|max_length[11]',
		'destinatario' => 'required|integer|max_length[11]',
		'titulo' => 'max_length[65535]',
		'mensagem' => 'required|max_length[65535]',
		'anexo' => 'max_length[65535]',
		'datacadastro' => 'required|valid_datetime',
		'status' => 'required|integer|max_length[2]'
	];

}
