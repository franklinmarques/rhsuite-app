<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_equipes_model extends MY_Model
{
	protected static $table = 'dimensionamento_equipes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'is_natural_no_zero|max_length[11]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'id_setor' => 'is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'total_componentes' => 'required|integer|max_length[11]'
	];

}
