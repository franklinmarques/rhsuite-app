<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_realizacoes_model extends MY_Model
{
	protected static $table = 'facilities_realizacoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo' => 'required|is_natural_no_zero|max_length[11]',
		'mes' => 'required|integer|max_length[2]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'pendencias' => 'required|numeric|max_length[1]',
		'id_usuario_vistoriador' => 'is_natural_no_zero|max_length[11]',
		'tipo_executor' => 'exact_length[1]',
		'status' => 'required|exact_length[1]'
	];

}
