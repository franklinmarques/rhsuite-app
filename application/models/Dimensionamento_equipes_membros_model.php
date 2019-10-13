<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_equipes_membros_model extends MY_Model
{
	protected static $table = 'dimensionamento_equipes_membros';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_equipe' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]'
	];

}
