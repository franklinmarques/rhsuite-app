<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_medicoes_resultado_model extends MY_Model
{
	protected static $table = 'dimensionamento_medicoes_resultado';

	protected static $createdField = 'data_cadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_crono_analise' => 'required|is_natural_no_zero|max_length[11]',
		'id_executor' => 'required|is_natural_no_zero|max_length[11]',
		'id_processo' => 'is_natural_no_zero|max_length[11]',
		'id_atividade' => 'is_natural_no_zero|max_length[11]',
		'id_etapa' => 'is_natural_no_zero|max_length[11]',
		'grau_complexidade' => 'numeric|max_length[1]',
		'tamanho_item' => 'numeric|max_length[1]',
		'soma_menor' => 'required|decimal|max_length[10]',
		'soma_media' => 'required|decimal|max_length[10]',
		'soma_maior' => 'required|decimal|max_length[10]',
		'mao_obra_menor' => 'required|decimal|max_length[10]',
		'mao_obra_media' => 'required|decimal|max_length[10]',
		'mao_obra_maior' => 'required|decimal|max_length[10]',
		'data_cadastro' => 'required|valid_datetime'
	];

}
