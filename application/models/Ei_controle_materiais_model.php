<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_controle_materiais_model extends MY_Model
{
	protected static $table = 'ei_controle_materiais';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_frequencia' => 'required|is_natural_no_zero|max_length[11]',
		'id_insumo' => 'required|is_natural_no_zero|max_length[11]',
		'qtde' => 'required|integer|max_length[11]'
	];

}
