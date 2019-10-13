<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_itens_model extends MY_Model
{
	protected static $table = 'facilities_itens';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_sala' => 'required|is_natural_no_zero|max_length[11]',
		'ativo' => 'required|numeric|max_length[1]',
		'nome' => 'required|max_length[50]',
		'codigo' => 'max_length[10]',
		'tipo' => 'max_length[50]',
		'data_entrada_operacao' => 'valid_date',
		'anos_duracao' => 'integer|max_length[3]',
		'periodicidade_vistoria' => 'exact_length[1]',
		'mes_vistoria_jan' => 'numeric|max_length[1]',
		'mes_vistoria_fev' => 'numeric|max_length[1]',
		'mes_vistoria_mar' => 'numeric|max_length[1]',
		'mes_vistoria_abr' => 'numeric|max_length[1]',
		'mes_vistoria_mai' => 'numeric|max_length[1]',
		'mes_vistoria_jun' => 'numeric|max_length[1]',
		'mes_vistoria_jul' => 'numeric|max_length[1]',
		'mes_vistoria_ago' => 'numeric|max_length[1]',
		'mes_vistoria_set' => 'numeric|max_length[1]',
		'mes_vistoria_out' => 'numeric|max_length[1]',
		'mes_vistoria_nov' => 'numeric|max_length[1]',
		'mes_vistoria_dez' => 'numeric|max_length[1]',
		'periodicidade_manutencao' => 'exact_length[1]',
		'mes_manutencao_jan' => 'numeric|max_length[1]',
		'mes_manutencao_fev' => 'numeric|max_length[1]',
		'mes_manutencao_mar' => 'numeric|max_length[1]',
		'mes_manutencao_abr' => 'numeric|max_length[1]',
		'mes_manutencao_mai' => 'numeric|max_length[1]',
		'mes_manutencao_jun' => 'numeric|max_length[1]',
		'mes_manutencao_jul' => 'numeric|max_length[1]',
		'mes_manutencao_ago' => 'numeric|max_length[1]',
		'mes_manutencao_set' => 'numeric|max_length[1]',
		'mes_manutencao_out' => 'numeric|max_length[1]',
		'mes_manutencao_nov' => 'numeric|max_length[1]',
		'mes_manutencao_dez' => 'numeric|max_length[1]'
	];

}
