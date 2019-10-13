<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_contratos_model extends MY_Model
{
	protected static $table = 'usuarios_contratos';

	protected static $autoIncrement = false;

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'data_assinatura' => 'required|valid_date',
		'id_depto' => 'required|integer|max_length[11]',
		'id_area' => 'required|integer|max_length[11]',
		'id_setor' => 'required|integer|max_length[11]',
		'id_cargo' => 'required|integer|max_length[11]',
		'id_funcao' => 'required|integer|max_length[11]',
		'contrato' => 'max_length[255]',
		'valor_posto' => 'required|decimal|max_length[11]',
		'conversor_dia' => 'decimal|max_length[11]',
		'conversor_hora' => 'decimal|max_length[11]'
	];

}
