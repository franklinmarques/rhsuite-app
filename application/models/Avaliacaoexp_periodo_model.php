<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_periodo_model extends MY_Model
{
	protected static $table = 'avaliacaoexp_periodo';

	protected static $primaryKey = 'id_avaliado';

	protected static $autoIncrement = false;

	protected static $createdField = 'data';

	protected static $updatedField = 'data';

	protected $validationRules = [
		'id_avaliado' => 'required|is_natural_no_zero|max_length[11]',
		'pontos_fortes' => 'max_length[4294967295]',
		'pontos_fracos' => 'max_length[4294967295]',
		'feedback1' => 'max_length[4294967295]',
		'data_feedback1' => 'valid_date',
		'feedback2' => 'max_length[4294967295]',
		'data_feedback2' => 'valid_date',
		'feedback3' => 'max_length[4294967295]',
		'data_feedback3' => 'valid_date',
		'parecer_final' => 'exact_length[1]',
		'data' => 'valid_datetime'
	];

}
