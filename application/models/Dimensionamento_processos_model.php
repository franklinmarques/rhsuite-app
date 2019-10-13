<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_processos_model extends MY_Model
{
	protected static $table = 'dimensionamento_processos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'required|is_natural_no_zero|max_length[11]',
		'id_area' => 'required|is_natural_no_zero|max_length[11]',
		'id_setor' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]'
	];

}
