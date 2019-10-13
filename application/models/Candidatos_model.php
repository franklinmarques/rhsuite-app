<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Candidatos_model extends MY_Model
{
	protected static $table = 'candidatos';

	protected static $createdField = 'data_inscricao';

	protected static $updatedField = 'data_edicao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'data_nascimento' => 'valid_date',
		'sexo' => 'max_length[1]',
		'estado_civil' => 'integer|max_length[11]',
		'nome_mae' => 'max_length[255]',
		'nome_pai' => 'max_length[255]',
		'cpf' => 'max_length[14]',
		'rg' => 'max_length[13]',
		'pis' => 'max_length[14]',
		'logradouro' => 'max_length[255]',
		'numero' => 'integer|max_length[11]',
		'complemento' => 'max_length[255]',
		'bairro' => 'max_length[50]',
		'cidade' => 'integer|max_length[11]',
		'estado' => 'integer|max_length[2]',
		'cep' => 'max_length[9]',
		'escolaridade' => 'integer|max_length[11]',
		'deficiencia' => 'integer|max_length[11]',
		'foto' => 'max_length[255]',
		'telefone' => 'required|max_length[255]',
		'email' => 'required|max_length[255]',
		'senha' => 'required|max_length[32]',
		'token' => 'required|max_length[255]',
		'data_inscricao' => 'valid_datetime',
		'fonte_contratacao' => 'max_length[30]',
		'data_edicao' => 'valid_datetime',
		'nivel_acesso' => 'required|exact_length[1]',
		'url' => 'max_length[255]',
		'arquivo_curriculo' => 'max_length[255]',
		'status' => 'required|exact_length[1]'
	];

}
