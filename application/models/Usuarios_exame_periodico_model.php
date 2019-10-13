<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_exame_periodico_model extends MY_Model
{
	protected static $table = 'usuarios_exame_periodico';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'data_programada' => 'required|valid_date',
		'data_realizacao' => 'valid_date|after_or_equal_date[data_programada]',
		'data_entrega' => 'valid_date|after_or_equal_date[data_realizacao]',
		'data_entrega_copia' => 'valid_date|after_or_equal_date[data_entrega]',
		'local_exame' => 'max_length[255]',
		'observacoes' => 'max_length[65535]'
	];

}
