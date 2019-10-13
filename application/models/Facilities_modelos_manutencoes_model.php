<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_modelos_manutencoes_model extends MY_Model
{
	protected static $table = 'facilities_modelos_manutencoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'id_manutencao' => 'required|is_natural_no_zero|max_length[11]',
		'status' => 'numeric|max_length[1]'
	];

}
