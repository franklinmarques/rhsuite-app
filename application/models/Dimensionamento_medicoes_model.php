<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_medicoes_model extends MY_Model
{
	protected static $table = 'dimensionamento_medicoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_executor' => 'required|is_natural_no_zero|max_length[11]',
		'id_etapa' => 'required|is_natural_no_zero|max_length[11]',
		'tempo_inicio' => 'required|decimal|max_length[10]',
		'tempo_termino' => 'required|decimal|max_length[10]',
		'tempo_gasto' => 'decimal|max_length[10]',
		'quantidade' => 'decimal|max_length[10]',
		'tempo_unidade' => 'decimal|max_length[10]',
		'indice_mao_obra' => 'decimal|max_length[10]',
		'complexidade' => 'integer|max_length[11]',
		'tipo_item' => 'integer|max_length[11]',
		'medicao_calculada' => 'required|numeric|max_length[1]',
		'valor_min_calculado' => 'decimal|max_length[10]',
		'valor_medio_calculado' => 'decimal|max_length[10]',
		'valor_max_calculado' => 'decimal|max_length[10]',
		'mao_obra_min_calculada' => 'decimal|max_length[10]',
		'mao_obra_media_calculada' => 'decimal|max_length[10]',
		'mao_obra_max_calculada' => 'decimal|max_length[10]',
		'status' => 'required|numeric|max_length[1]'
	];

}
