<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_afastamento_model extends MY_Model
{
	protected static $table = 'usuarios_afastamento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'data_afastamento' => 'required|valid_date',
		'motivo_afastamento' => 'integer|max_length[1]',
		'motivo_afastamento_bck' => 'max_length[255]',
		'data_pericia_medica' => 'valid_date',
		'data_limite_beneficio' => 'valid_date',
		'data_retorno' => 'valid_date',
		'historico_afastamento' => 'max_length[65535]'
	];

}
