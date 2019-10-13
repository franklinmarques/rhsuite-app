<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_desempenho_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_desempenho';

	protected static $primaryKey = 'id_avaliador';

	protected static $autoIncrement = false;

	protected static $createdField = 'data';

	protected static $updatedField = 'data';

	protected $validationRules = [
		'id_avaliador' => 'required|is_natural_no_zero|max_length[11]',
		'pontos_fortes' => 'max_length[4294967295]',
		'pontos_fracos' => 'max_length[4294967295]',
		'observacoes' => 'max_length[4294967295]',
		'data' => 'valid_datetime'
	];

}
