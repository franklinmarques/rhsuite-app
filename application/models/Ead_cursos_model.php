<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_cursos_model extends MY_Model
{
	protected static $table = 'cursos';

	protected static $createdField = 'data_acesso';

	protected static $updatedField = 'data_editado';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'publico' => 'required|integer|max_length[1]',
		'gratuito' => 'required|integer|max_length[1]',
		'descricao' => 'max_length[4294967295]',
		'data_cadastro' => 'required|valid_datetime',
		'data_editado' => 'valid_datetime',
		'horas_duracao' => 'required|integer|max_length[11]',
		'objetivos' => 'max_length[65535]',
		'competencias_genericas' => 'max_length[100]',
		'competencias_especificas' => 'max_length[100]',
		'competencias_comportamentais' => 'max_length[100]',
		'categoria' => 'max_length[100]',
		'id_categoria' => 'is_natural_no_zero|max_length[11]',
		'area_conhecimento' => 'max_length[100]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'consultor' => 'max_length[100]',
		'foto_consultor' => 'max_length[255]',
		'curriculo' => 'max_length[65535]',
		'foto_treinamento' => 'max_length[255]',
		'pre_requisitos' => 'max_length[100]',
		'progressao_linear' => 'required|integer|max_length[1]',
		'status' => 'required|integer|max_length[1]',
		'id_copia' => 'is_natural_no_zero|max_length[11]'
	];

}
