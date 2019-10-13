<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Papd_mif_model extends MY_Model
{
	protected static $table = 'papd_mif';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_paciente' => 'required|is_natural_no_zero|max_length[11]',
		'avaliador' => 'required|max_length[255]',
		'data_avaliacao' => 'required|valid_date',
		'mif' => 'integer|max_length[3]',
		'observacoes' => 'max_length[4294967295]',
		'alimentacao' => 'numeric|max_length[1]',
		'arrumacao' => 'numeric|max_length[1]',
		'banho' => 'numeric|max_length[1]',
		'vestimenta_superior' => 'numeric|max_length[1]',
		'vestimenta_inferior' => 'numeric|max_length[1]',
		'higiene_pessoal' => 'numeric|max_length[1]',
		'controle_vesical' => 'numeric|max_length[1]',
		'controle_intestinal' => 'numeric|max_length[1]',
		'leito_cadeira' => 'numeric|max_length[1]',
		'sanitario' => 'numeric|max_length[1]',
		'banheiro_chuveiro' => 'numeric|max_length[1]',
		'marcha' => 'numeric|max_length[1]',
		'cadeira_rodas' => 'numeric|max_length[1]',
		'escadas' => 'numeric|max_length[1]',
		'compreensao_ambas' => 'numeric|max_length[1]',
		'compreensao_visual' => 'numeric|max_length[1]',
		'expressao_verbal' => 'numeric|max_length[1]',
		'expressao_nao_verbal' => 'numeric|max_length[1]',
		'interacao_social' => 'numeric|max_length[1]',
		'resolucao_problemas' => 'numeric|max_length[1]',
		'memoria' => 'numeric|max_length[1]'
	];

}
