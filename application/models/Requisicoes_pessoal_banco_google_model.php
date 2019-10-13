<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_banco_google_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_banco_google';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_requisicao' => 'required|is_natural_no_zero|max_length[11]',
		'cliente' => 'required|integer|max_length[11]',
		'nome_candidato' => 'required|max_length[255]',
		'cargo' => 'required|integer|max_length[11]',
		'cidade' => 'required|integer|max_length[11]',
		'deficiencia' => 'integer|max_length[11]',
		'telefone' => 'max_length[50]',
		'fonte_contratacao' => 'integer|max_length[11]',
		'data_captacao' => 'valid_date',
		'data_entrevista_rh' => 'valid_date',
		'resultado-entrevista_rh' => 'integer|max_length[11]',
		'data_entrevista_cliente' => 'valid_date',
		'resultado_entrevista_cliente' => 'integer|max_length[11]',
		'status' => 'integer|max_length[11]',
		'observacoes' => 'max_length[4294967295]'
	];

}
