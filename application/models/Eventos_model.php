<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos_model extends MY_Model
{
	protected static $table = 'eventos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'date_from' => 'required|valid_datetime',
		'date_to' => 'required|valid_datetime',
		'type' => 'required|integer|max_length[3]',
		'title' => 'required|max_length[165]',
		'description' => 'required|max_length[4294967295]',
		'link' => 'max_length[300]',
		'color' => 'max_length[7]',
		'status' => 'required|integer|max_length[1]',
		'usuario' => 'integer|max_length[11]',
		'usuario_referenciado' => 'integer|max_length[11]'
	];

}
