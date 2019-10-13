<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_contratos_model extends MY_Model
{
	protected static $table = 'alocacao_contratos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[100]',
		'depto' => 'required|max_length[255]',
		'area' => 'required|max_length[255]',
		'contrato' => 'required|max_length[255]',
		'data_assinatura' => 'valid_date'
	];

}
