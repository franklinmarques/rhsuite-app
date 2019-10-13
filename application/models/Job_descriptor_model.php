<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_descriptor_model extends MY_Model
{
	protected static $table = 'job_descriptor';

	protected static $createdField = 'data';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_cargo' => 'required|is_natural_no_zero|max_length[11]',
		'id_funcao' => 'required|is_natural_no_zero|max_length[11]',
		'versao' => 'required|max_length[255]',
		'data' => 'required|valid_timestamp',
		'sumario' => 'required|numeric|max_length[1]',
		'formacao_experiencia' => 'required|numeric|max_length[1]',
		'condicoes_gerais_exercicio' => 'required|numeric|max_length[1]',
		'codigo_internacional_CIUO88' => 'required|numeric|max_length[1]',
		'notas' => 'required|numeric|max_length[1]',
		'recursos_trabalho' => 'required|numeric|max_length[1]',
		'atividades' => 'required|numeric|max_length[1]',
		'responsabilidades' => 'required|numeric|max_length[1]',
		'conhecimentos_habilidades' => 'required|numeric|max_length[1]',
		'habilidades_basicas' => 'required|numeric|max_length[1]',
		'habilidades_intermediarias' => 'required|numeric|max_length[1]',
		'habilidades_avancadas' => 'required|numeric|max_length[1]',
		'ambiente_trabalho' => 'required|numeric|max_length[1]',
		'condicoes_trabalho' => 'required|numeric|max_length[1]',
		'esforcos_fisicos' => 'required|numeric|max_length[1]',
		'grau_autonomia' => 'required|numeric|max_length[1]',
		'grau_complexidade' => 'required|numeric|max_length[1]',
		'grau_iniciativa' => 'required|numeric|max_length[1]',
		'competencias_tecnicas' => 'required|numeric|max_length[1]',
		'competencias_comportamentais' => 'required|numeric|max_length[1]',
		'tempo_experiencia' => 'required|numeric|max_length[1]',
		'formacao_minima' => 'required|numeric|max_length[1]',
		'formacao_plena' => 'required|numeric|max_length[1]',
		'esforcos_mentais' => 'required|numeric|max_length[1]',
		'grau_pressao' => 'required|numeric|max_length[1]',
		'campo_livre1' => 'max_length[255]',
		'campo_livre2' => 'max_length[255]',
		'campo_livre3' => 'max_length[255]',
		'campo_livre4' => 'max_length[255]',
		'campo_livre5' => 'max_length[255]',
		'id_versao_anterior' => 'integer|max_length[11]'
	];

}
