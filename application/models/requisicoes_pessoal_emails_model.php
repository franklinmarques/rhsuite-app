<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_emails_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_emails';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'is_natural_no_zero|max_length[11]',
		'colaborador' => 'required|max_length[255]',
		'email' => 'required|max_length[255]',
		'tipo_usuario' => 'required|integer|max_length[1]',
		'tipo_email' => 'integer|max_length[1]'
	];

}
