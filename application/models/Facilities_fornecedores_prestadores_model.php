<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_fornecedores_prestadores_model extends MY_Model
{
	protected static $table = 'facilities_fornecedores_prestadores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'tipo' => 'required|integer|max_length[1]',
		'vinculo' => 'max_length[255]',
		'pessoa_contato' => 'max_length[255]',
		'telefone' => 'max_length[255]',
		'email' => 'max_length[255]',
		'status' => 'required|numeric|max_length[1]'
	];

}
