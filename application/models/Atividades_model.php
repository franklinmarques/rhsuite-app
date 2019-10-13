<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades_model extends MY_Model
{
	protected static $table = 'atividades';

	protected static $createdField = 'data_cadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'required|in_list[G,O]',
		'prioridade' => 'required|integer|max_length[1]',
		'atividade' => 'required|max_length[65535]',
		'data_cadastro' => 'required|valid_datetime',
		'data_limite' => 'required|valid_datetime',
		'data_lembrete' => 'required|valid_date',
		'data_fechamento' => 'valid_datetime',
		'status' => 'required|integer|max_length[1]',
		'observacoes' => 'max_length[65535]',
		'id_mae' => 'is_natural_no_zero|max_length[11]'
	];

	protected static $tipo = [
		'G' => 'Gestão',
		'O' => 'Operacional'
	];

	protected static $prioridade = [
		'0' => 'Baixa',
		'1' => 'Média',
		'2' => 'Alta'
	];

	protected static $status = [
		'0' => 'Não-finalizado',
		'1' => 'Finalizado'
	];

}
