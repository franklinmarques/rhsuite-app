<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_planos_trabalho_model extends MY_Model
{
	protected static $table = 'dimensionamento_planos_trabalho';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'data_inicio' => 'required|valid_date',
		'data_termino' => 'required|valid_date',
		'plano_diario' => 'required|numeric|max_length[1]',
		'status' => 'required|exact_length[1]'
	];

}
