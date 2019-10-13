<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_quati_caracteristicas_model extends MY_Model
{
	protected static $table = 'pesquisa_quati_caracteristicas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'tipo_comportamental' => 'required|exact_length[1]',
		'nome' => 'required|max_length[100]'
	];

}
