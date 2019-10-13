<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_estagios_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_estagios';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'destino_email' => 'required|max_length[255]',
		'email_responsavel' => 'required|max_length[255]',
		'mensagem' => 'max_length[4294967295]'
	];

}
