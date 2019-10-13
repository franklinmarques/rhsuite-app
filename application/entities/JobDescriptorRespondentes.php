<?php

include_once APPPATH . 'entities/Entity.php';

class JobDescriptorRespondentes extends Entity
{
	protected $id;
	protected $id_descritor;
	protected $id_usuario;
	protected $sumario;
	protected $formacao_experiencia;
	protected $condicoes_gerais_exercicio;
	protected $codigo_internacional_CIUO88;
	protected $notas;
	protected $recursos_trabalho;
	protected $atividades;
	protected $responsabilidades;
	protected $conhecimentos_habilidades;
	protected $habilidades_basicas;
	protected $habilidades_intermediarias;
	protected $habilidades_avancadas;
	protected $ambiente_trabalho;
	protected $condicoes_trabalho;
	protected $esforcos_fisicos;
	protected $grau_autonomia;
	protected $grau_complexidade;
	protected $grau_iniciativa;
	protected $competencias_tecnicas;
	protected $competencias_comportamentais;
	protected $tempo_experiencia;
	protected $formacao_minima;
	protected $formacao_plena;
	protected $esforcos_mentais;
	protected $grau_pressao;
	protected $campo_livre1;
	protected $campo_livre2;
	protected $campo_livre3;
	protected $campo_livre4;
	protected $campo_livre5;

	protected $casts = [
		'id' => 'int',
		'id_descritor' => 'int',
		'id_usuario' => 'int',
		'sumario' => '?string',
		'formacao_experiencia' => '?string',
		'condicoes_gerais_exercicio' => '?string',
		'codigo_internacional_CIUO88' => '?string',
		'notas' => '?string',
		'recursos_trabalho' => '?string',
		'atividades' => '?string',
		'responsabilidades' => '?string',
		'conhecimentos_habilidades' => '?string',
		'habilidades_basicas' => '?string',
		'habilidades_intermediarias' => '?string',
		'habilidades_avancadas' => '?string',
		'ambiente_trabalho' => '?string',
		'condicoes_trabalho' => '?string',
		'esforcos_fisicos' => '?string',
		'grau_autonomia' => '?string',
		'grau_complexidade' => '?string',
		'grau_iniciativa' => '?string',
		'competencias_tecnicas' => '?string',
		'competencias_comportamentais' => '?string',
		'tempo_experiencia' => '?string',
		'formacao_minima' => '?string',
		'formacao_plena' => '?string',
		'esforcos_mentais' => '?string',
		'grau_pressao' => '?string',
		'campo_livre1' => '?string',
		'campo_livre2' => '?string',
		'campo_livre3' => '?string',
		'campo_livre4' => '?string',
		'campo_livre5' => '?string'
	];

}
