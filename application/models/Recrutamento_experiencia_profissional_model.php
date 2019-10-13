<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_experiencia_profissional_model extends MY_Model
{
	protected static $table = 'recrutamento_experiencia_profissional';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'instituicao' => 'required|max_length[255]',
		'data_entrada' => 'required|valid_date',
		'data_saida' => 'valid_date',
		'cargo_entrada' => 'required|max_length[255]',
		'cargo_saida' => 'max_length[255]',
		'salario_entrada' => 'required|decimal|max_length[11]',
		'salario_saida' => 'decimal|max_length[11]',
		'motivo_saida' => 'max_length[255]',
		'realizacoes' => 'max_length[4294967295]'
	];

}
