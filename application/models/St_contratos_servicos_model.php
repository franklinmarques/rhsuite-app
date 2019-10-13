<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_contratos_servicos_model extends MY_Model
{
	protected static $table = 'alocacao_servicos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_contrato' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'required|integer|max_length[1]',
		'descricao' => 'required|max_length[255]',
		'data_reajuste' => 'valid_date',
		'valor' => 'required|decimal|max_length[11]'
	];

}
