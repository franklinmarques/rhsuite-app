<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_model extends MY_Model
{
	protected static $table = 'recrutamento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]',
		'id_usuario_EMPRESA' => 'required|is_natural_no_zero|max_length[11]',
		'data_inicio' => 'required|valid_datetime',
		'data_termino' => 'required|valid_datetime',
		'requisitante' => 'required|max_length[50]',
		'tipo_vaga' => 'exact_length[1]',
		'status' => 'exact_length[1]'
	];

}
