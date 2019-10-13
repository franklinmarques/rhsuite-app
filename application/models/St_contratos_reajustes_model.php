<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_contratos_reajustes_model extends MY_Model
{
	protected static $table = 'alocacao_reajuste';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_cliente' => 'required|is_natural_no_zero|max_length[11]',
		'data_reajuste' => 'required|valid_date',
		'valor_indice' => 'required|decimal|max_length[12]'
	];

}
