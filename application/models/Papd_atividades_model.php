<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Papd_atividades_model extends MY_Model
{
	protected static $table = 'papd_atividades';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'valor' => 'required|decimal|max_length[11]',
		'id_instituicao' => 'required|is_natural_no_zero|max_length[11]'
	];

}
