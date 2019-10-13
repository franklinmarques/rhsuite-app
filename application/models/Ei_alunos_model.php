<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_alunos_model extends MY_Model
{
	protected static $table = 'ei_alunos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[100]',
		'id_escola' => 'is_natural_no_zero|max_length[11]',
		'endereco' => 'max_length[255]',
		'numero' => 'integer|max_length[11]',
		'complemento' => 'max_length[255]',
		'municipio' => 'max_length[100]',
		'telefone' => 'max_length[50]',
		'contato' => 'max_length[255]',
		'email' => 'max_length[255]',
		'cep' => 'max_length[20]',
		'hipotese_diagnostica' => 'max_length[255]',
		'nome_responsavel' => 'max_length[100]',
		'observacoes' => 'max_length[65535]',
		'data_matricula' => 'valid_date',
		'data_afastamento' => 'valid_date',
		'data_desligamento' => 'valid_date',
		'status' => 'required|exact_length[1]'
	];

}
