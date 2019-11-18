<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_contas_unidades_model extends MY_Model
{
	protected static $table = 'facilities_contas_unidades';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_conta_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]'
	];

}