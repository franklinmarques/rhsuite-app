<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_testes_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_testes';

	protected static $createdField = 'data_acesso';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_candidato' => 'required|is_natural_no_zero|max_length[11]',
		'tipo_teste' => 'required|exact_length[1]',
		'id_modelo' => 'is_natural_no_zero|max_length[11]',
		'nome' => 'max_length[255]',
		'data_inicio' => 'required|valid_datetime',
		'data_termino' => 'valid_datetime',
		'minutos_duracao' => 'integer|max_length[11]',
		'aleatorizacao' => 'exact_length[1]',
		'data_acesso' => 'valid_datetime',
		'data_envio' => 'valid_datetime',
		'nota_aproveitamento' => 'decimal|max_length[4]',
		'observacoes' => 'max_length[4294967295]',
		'status' => 'exact_length[1]'
	];

}
