<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_candidatos_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_candidatos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_requisicao' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'is_natural_no_zero|max_length[11]',
		'id_usuario_banco' => 'is_natural_no_zero|max_length[11]',
		'status' => 'exact_length[1]',
		'data_selecao' => 'valid_datetime',
		'resultado_selecao' => 'exact_length[1]',
		'data_requisitante' => 'valid_datetime',
		'resultado_requisitante' => 'exact_length[1]',
		'antecedentes_criminais' => 'numeric|max_length[1]',
		'restricoes_financeiras' => 'numeric|max_length[1]',
		'data_exame_admissional' => 'valid_datetime',
		'endereco_exame_admissional' => 'max_length[65535]',
		'resultado_exame_admissional' => 'numeric|max_length[1]',
		'aprovado' => 'integer|max_length[1]',
		'data_admissao' => 'valid_date',
		'observacoes' => 'max_length[4294967295]',
		'desempenho_perfil' => 'exact_length[1]'
	];

}
