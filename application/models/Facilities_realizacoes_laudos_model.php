<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_realizacoes_laudos_model extends MY_Model
{
	protected static $table = 'facilities_realizacoes_laudos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_realizacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_item' => 'required|is_natural_no_zero|max_length[11]',
		'arquivo' => 'required|max_length[255]',
		'tipo_mime' => 'required|max_length[50]',
		'data_cadastro' => 'required|valid_datetime',
		'local_armazem' => 'max_length[255]',
		'sala_box' => 'max_length[255]',
		'arquivo_fisico' => 'max_length[255]',
		'pasta_caixa' => 'max_length[255]',
		'codigo_localizador' => 'max_length[32]'
	];

}
