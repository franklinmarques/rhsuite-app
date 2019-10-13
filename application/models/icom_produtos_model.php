<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_produtos_model extends MY_Model
{
	protected static $table = 'icom_produtos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_setor' => 'required|is_natural_no_zero|max_length[11]',
		'codigo' => 'required|max_length[20]',
		'nome' => 'required|max_length[255]',
		'tipo' => 'required|in_list[P,S]',
		'preco' => 'required|numeric|greater_than_equal_to[0]|max_length[11]',
		'custo' => 'numeric|greater_than_equal_to[0]|max_length[10]',
		'tipo_cobranca' => 'required|in_list[H,M,C,E]',
		'centro_custo' => 'max_length[255]',
		'complementos' => 'max_length[4294967295]'
	];

	protected static $tipo = ['P' => 'Produto', 'S' => 'Serviço'];

	protected static $tipoCobranca = [
		'H' => 'Por hora',
		'M' => 'Por mês',
		'C' => 'Por colaborador/mês',
		'E' => 'Por entrega'
	];

}
