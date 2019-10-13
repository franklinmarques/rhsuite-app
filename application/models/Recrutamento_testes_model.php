<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_testes_model extends MY_Model
{
	protected static $table = 'recrutamento_testes';

	protected static $createdField = 'data_acesso';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_candidato' => 'required|integer|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'data_inicio' => 'required|valid_datetime',
		'data_termino' => 'required|valid_datetime',
		'minutos_duracao' => 'integer|max_length[11]',
		'aleatorizacao' => 'exact_length[1]',
		'data_acesso' => 'valid_datetime',
		'data_envio' => 'valid_datetime',
		'status' => 'exact_length[1]'
	];

}
