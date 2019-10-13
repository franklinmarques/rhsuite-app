<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_candidatos_model extends MY_Model
{
	protected static $table = 'recrutamento_candidatos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_cargo' => 'required|integer|max_length[11]',
		'id_usuario' => 'required|integer|max_length[11]'
	];

}
