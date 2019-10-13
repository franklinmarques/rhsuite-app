<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_cargos_model extends MY_Model
{
	protected static $table = 'recrutamento_cargos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_recrutamento' => 'required|integer|max_length[11]',
		'cargo' => 'required|max_length[50]'
	];

}
