<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_mapa_unidades_model extends MY_Model
{
	protected static $table = 'ei_mapa_unidades';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisao' => 'integer|max_length[11]',
		'id_escola' => 'is_natural_no_zero|max_length[11]',
		'escola' => 'required|max_length[255]',
		'municipio' => 'required|max_length[255]'
	];

}
