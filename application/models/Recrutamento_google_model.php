<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_google_model extends MY_Model
{
	protected static $table = 'recrutamento_google';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'cliente' => 'max_length[255]',
		'cargo' => 'max_length[200]',
		'cidade' => 'max_length[200]',
		'nome' => 'required|max_length[255]',
		'data_nascimento' => 'valid_date',
		'deficiencia' => 'max_length[255]',
		'telefone' => 'max_length[255]',
		'email' => 'max_length[255]',
		'fonte_contratacao' => 'max_length[200]',
		'status' => 'max_length[200]',
		'data_entrevista_rh' => 'max_length[200]',
		'resultado_entrevista_rh' => 'max_length[200]',
		'data_entrevista_cliente' => 'max_length[200]',
		'resultado_entrevista_cliente' => 'max_length[200]',
		'observacoes' => 'max_length[4294967295]'
	];

}
