<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gestao_pessoal_treinamentos_model extends MY_Model
{
	protected static $table = 'gestao_pessoal_treinamentos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'required|is_natural_no_zero|max_length[11]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'mes' => 'required|integer|max_length[2]',
		'total_colaboradores' => 'required|integer|max_length[11]'
	];

}
