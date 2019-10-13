<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_etapas_model extends MY_Model
{
	protected static $table = 'dimensionamento_etapas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[10]',
		'id_atividade' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'grau_complexidade' => 'numeric|max_length[1]',
		'tamanho_item' => 'numeric|max_length[1]',
		'peso_item' => 'decimal|max_length[10]'
	];

}
