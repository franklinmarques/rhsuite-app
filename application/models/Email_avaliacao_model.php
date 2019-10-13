<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Email_avaliacao_model extends MY_Model
{
	protected static $table = 'email_avaliacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliacao' => 'required|integer|max_length[11]',
		'texto_inicio' => 'required|max_length[65535]',
		'texto_cobranca' => 'required|max_length[65535]',
		'texto_fim' => 'required|max_length[65535]'
	];

}
