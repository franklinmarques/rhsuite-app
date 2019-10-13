<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_atividades_model extends MY_Model
{
	protected static $table = 'dimensionamento_atividades';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_processo' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]'
	];

}
