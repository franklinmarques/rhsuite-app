<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_descriptor_respondentes_model extends MY_Model
{
	protected static $table = 'job_descriptor_respondentes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_descritor' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'sumario' => 'max_length[4294967295]',
		'formacao_experiencia' => 'max_length[4294967295]',
		'condicoes_gerais_exercicio' => 'max_length[4294967295]',
		'codigo_internacional_CIUO88' => 'max_length[4294967295]',
		'notas' => 'max_length[4294967295]',
		'recursos_trabalho' => 'max_length[4294967295]',
		'atividades' => 'max_length[4294967295]',
		'responsabilidades' => 'max_length[4294967295]',
		'conhecimentos_habilidades' => 'max_length[4294967295]',
		'habilidades_basicas' => 'max_length[4294967295]',
		'habilidades_intermediarias' => 'max_length[4294967295]',
		'habilidades_avancadas' => 'max_length[4294967295]',
		'ambiente_trabalho' => 'max_length[4294967295]',
		'condicoes_trabalho' => 'max_length[4294967295]',
		'esforcos_fisicos' => 'max_length[4294967295]',
		'grau_autonomia' => 'max_length[4294967295]',
		'grau_complexidade' => 'max_length[4294967295]',
		'grau_iniciativa' => 'max_length[4294967295]',
		'competencias_tecnicas' => 'max_length[4294967295]',
		'competencias_comportamentais' => 'max_length[4294967295]',
		'tempo_experiencia' => 'max_length[4294967295]',
		'formacao_minima' => 'max_length[4294967295]',
		'formacao_plena' => 'max_length[4294967295]',
		'esforcos_mentais' => 'max_length[4294967295]',
		'grau_pressao' => 'max_length[4294967295]',
		'campo_livre1' => 'max_length[4294967295]',
		'campo_livre2' => 'max_length[4294967295]',
		'campo_livre3' => 'max_length[4294967295]',
		'campo_livre4' => 'max_length[4294967295]',
		'campo_livre5' => 'max_length[4294967295]'
	];

}
