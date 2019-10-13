<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_banco_horas_supervisao_model extends MY_Model
{
	protected static $table = 'ei_banco_horas_supervisao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'depto' => 'required|max_length[255]',
		'id_diretoria' => 'required|integer|max_length[11]',
		'diretoria' => 'required|max_length[255]',
		'id_supervisor' => 'required|integer|max_length[11]',
		'supervisor' => 'required|max_length[255]',
		'municipio' => 'required|max_length[255]',
		'coordenador' => 'required|max_length[255]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'semestre' => 'required|numeric|max_length[1]'
	];

}
