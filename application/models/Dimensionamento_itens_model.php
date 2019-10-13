<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_itens_model extends MY_Model
{
	protected static $table = 'dimensionamento_itens';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_etapa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]',
		'descricao' => 'max_length[50]',
		'unidade_medida' => 'max_length[10]',
		'valor' => 'decimal|max_length[11]'
	];

}
