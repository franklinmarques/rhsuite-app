<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gestao_vagas_model extends MY_Model
{
	protected static $table = 'gestao_vagas';

	protected static $primaryKey = 'codigo';

	protected $validationRules = [
		'codigo' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'data_abertura' => 'required|valid_date',
		'status' => 'required|numeric|max_length[1]',
		'id_requisicao_pessoal' => 'required|is_natural_no_zero|max_length[11]',
		'id_cargo' => 'required|is_natural_no_zero|max_length[11]',
		'id_funcao' => 'required|is_natural_no_zero|max_length[11]',
		'formacao_minima' => 'integer|max_length[2]',
		'formacao_especifica_minima' => 'max_length[65535]',
		'perfil_profissional_desejado' => 'max_length[65535]',
		'quantidade' => 'required|integer|max_length[11]',
		'estado_vaga' => 'exact_length[2]',
		'cidade_vaga' => 'max_length[100]',
		'bairro_vaga' => 'max_length[255]',
		'tipo_vinculo' => 'required|numeric|max_length[1]',
		'remuneracao' => 'required|decimal|max_length[11]',
		'beneficios' => 'max_length[65535]',
		'horario_trabalho' => 'max_length[65535]',
		'contato_selecionador' => 'max_length[65535]'
	];

}
