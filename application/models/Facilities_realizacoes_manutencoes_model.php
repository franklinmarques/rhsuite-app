<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_realizacoes_manutencoes_model extends MY_Model
{
	protected static $table = 'facilities_realizacoes_manutencoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_realizacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_modelo_manutencao' => 'required|is_natural_no_zero|max_length[11]',
		'numero_os' => 'required|max_length[20]',
		'possui_problema' => 'required|numeric|max_length[1]',
		'vistoriado' => 'required|numeric|max_length[1]',
		'nao_aplicavel' => 'required|numeric|max_length[1]',
		'descricao_problema' => 'max_length[4294967295]',
		'observacoes' => 'max_length[4294967295]',
		'data_abertura' => 'required|valid_date',
		'data_realizacao' => 'valid_date',
		'realizacao_cat' => 'max_length[255]',
		'status' => 'exact_length[1]'
	];

}
