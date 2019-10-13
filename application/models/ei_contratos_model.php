<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_contratos_model extends MY_Model
{
	protected static $table = 'ei_contratos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_cliente' => 'required|is_natural_no_zero|max_length[11]',
		'contrato' => 'required|max_length[30]',
		'data_inicio' => 'required|valid_date',
		'data_termino' => 'required|valid_date',
		'data_reajuste1' => 'valid_date',
		'indice_reajuste1' => 'decimal|max_length[12]',
		'data_reajuste2' => 'valid_date',
		'indice_reajuste2' => 'decimal|max_length[12]',
		'data_reajuste3' => 'valid_date',
		'indice_reajuste3' => 'decimal|max_length[12]',
		'data_reajuste4' => 'valid_date',
		'indice_reajuste4' => 'decimal|max_length[12]',
		'data_reajuste5' => 'valid_date',
		'indice_reajuste5' => 'decimal|max_length[12]'
	];

}
