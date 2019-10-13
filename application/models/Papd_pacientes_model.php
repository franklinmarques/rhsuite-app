<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Papd_pacientes_model extends MY_Model
{
	protected static $table = 'papd_pacientes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'cpf' => 'max_length[14]',
		'data_nascimento' => 'required|valid_date',
		'sexo' => 'required|max_length[1]',
		'id_deficiencia' => 'is_natural_no_zero|max_length[11]',
		'cadastro_municipal' => 'max_length[30]',
		'id_hipotese_diagnostica' => 'is_natural_no_zero|max_length[11]',
		'logradouro' => 'max_length[255]',
		'numero' => 'integer|max_length[11]',
		'complemento' => 'max_length[255]',
		'bairro' => 'max_length[50]',
		'cidade' => 'is_natural_no_zero|max_length[11]',
		'cidade_nome' => 'max_length[255]',
		'estado' => 'is_natural_no_zero|max_length[2]',
		'cep' => 'max_length[9]',
		'nome_responsavel_1' => 'max_length[255]',
		'telefone_fixo_1' => 'max_length[255]',
		'nome_responsavel_2' => 'max_length[255]',
		'telefone_fixo_2' => 'max_length[255]',
		'telefone_celular_2' => 'max_length[255]',
		'data_ingresso' => 'required|valid_date',
		'data_inativo' => 'valid_date',
		'data_fila_espera' => 'valid_date',
		'data_afastamento' => 'valid_date',
		'contratante' => 'max_length[255]',
		'contrato' => 'max_length[255]',
		'id_instituicao' => 'required|integer|max_length[11]',
		'status' => 'required|exact_length[1]',
		'telefone_celular_1' => 'max_length[255]'
	];

}
