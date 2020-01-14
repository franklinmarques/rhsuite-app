<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_sessoes_profissionais_model extends MY_Model
{
	protected static $table = 'icom_sessoes_profissionais';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_sessao' => 'required|is_natural_no_zero|max_length[11]',
		'id_profissional_alocado' => 'required|is_natural_no_zero|max_length[11]',
		'valor_pagamento' => 'required|numeric|max_length[11]'
	];

}
