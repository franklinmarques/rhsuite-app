<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_ordens_servico_model extends MY_Model
{
	protected static $table = 'facilities_ordens_servico';

	protected static $primaryKey = 'numero_os';

	protected $validationRules = [
		'numero_os' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'integer|max_length[11]',
		'data_abertura' => 'required|valid_date',
		'data_resolucao_problema' => 'valid_date',
		'data_tratamento' => 'valid_date',
		'data_fechamento' => 'valid_date',
		'status' => 'required|exact_length[1]',
		'prioridade' => 'required|integer|max_length[1]',
		'id_requisitante' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'is_natural_no_zero|max_length[11]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'id_setor' => 'is_natural_no_zero|max_length[11]',
		'descricao_problema' => 'max_length[4294967295]',
		'descricao_solicitacao' => 'max_length[4294967295]',
		'complemento' => 'max_length[65535]',
		'observacoes' => 'max_length[4294967295]',
		'arquivo' => 'max_length[255]',
		'resolucao_satisfatoria' => 'exact_length[1]',
		'observacoes_positivas' => 'max_length[4294967295]',
		'observacoes_negativas' => 'max_length[4294967295]'
	];

}
