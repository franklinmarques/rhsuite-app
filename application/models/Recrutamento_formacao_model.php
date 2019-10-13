<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_formacao_model extends MY_Model
{
	protected static $table = 'recrutamento_formacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_escolaridade' => 'required|is_natural_no_zero|max_length[11]',
		'curso' => 'max_length[255]',
		'tipo' => 'exact_length[1]',
		'instituicao' => 'required|max_length[255]',
		'ano_conclusao' => 'is_natural_no_zero|max_length[4]',
		'concluido' => 'required|integer|max_length[1]'
	];

}
