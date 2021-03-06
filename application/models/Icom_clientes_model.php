<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_clientes_model extends MY_Model
{
	protected static $table = 'icom_clientes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'cnpj' => 'max_length[18]',
		'endereco' => 'max_length[255]',
		'observacoes' => 'max_length[65535]',
		'contato_principal' => 'max_length[255]',
		'telefone_contato_principal' => 'max_length[255]',
		'email_contato_principal' => 'valid_email|max_length[255]',
		'cargo_contato_principal' => 'max_length[50]',
		'contato_secundario' => 'max_length[255]',
		'telefone_contato_secundario' => 'max_length[255]',
		'email_contato_secundario' => 'valid_email|max_length[255]',
		'cargo_contato_secundario' => 'max_length[50]'
	];

}
