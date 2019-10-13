<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_executores_model extends MY_Model
{
	protected static $table = 'dimensionamento_executores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_crono_analise' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'required|exact_length[1]',
		'id_equipe' => 'is_natural_no_zero|max_length[11]',
		'id_usuario' => 'is_natural_no_zero|max_length[11]'
	];

}
