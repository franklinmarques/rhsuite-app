<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_valores_faturamento_model extends MY_Model
{
	protected static $table = 'ei_valores_faturamento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_contrato' => 'required|is_natural_no_zero|max_length[11]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'semestre' => 'required|numeric|max_length[1]',
		'id_cargo' => 'integer|max_length[11]',
		'id_funcao' => 'required|integer|max_length[11]',
		'qtde_horas' => 'decimal|max_length[11]',
		'valor' => 'decimal|max_length[11]',
		'valor_pagamento' => 'decimal|max_length[11]',
		'valor2' => 'decimal|max_length[11]',
		'valor_pagamento2' => 'decimal|max_length[11]'
	];

}
