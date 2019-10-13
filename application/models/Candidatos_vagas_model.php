<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Candidatos_vagas_model extends MY_Model
{
	protected static $table = 'candidatos_vagas';

	protected static $createdField = 'data_cadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_candidato' => 'required|is_natural_no_zero|max_length[11]',
		'codigo_vaga' => 'required|is_natural_no_zero|max_length[11]',
		'data_cadastro' => 'required|valid_datetime',
		'status' => 'exact_length[1]'
	];

}
