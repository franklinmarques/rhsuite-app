<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Competencias_resultado_model extends MY_Model
{
	protected static $table = 'competencias_resultado';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliador' => 'required|is_natural_no_zero|max_length[11]',
		'cargo_dimensao' => 'required|is_natural_no_zero|max_length[11]',
		'nivel' => 'is_natural|less_than_equal_to[5]',
		'atitude' => 'is_natural|multiple_of[10]|less_than_equal_to[100]'
	];

	protected static $nivel = [
		'0' => 'Nenhum conhecimento',
		'1' => 'Conhecimento básico',
		'2' => 'Conhecimento e prática básicos',
		'3' => 'Conhecimento e prática intermediários',
		'4' => 'Conhecimento e prática avancados',
		'5' => 'Especialista e multiplicador'
	];

}
