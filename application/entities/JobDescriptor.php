<?php

include_once APPPATH . 'entities/Entity.php';

class JobDescriptor extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_cargo;
	protected $id_funcao;
	protected $versao;
	protected $data;
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
	protected $id_versao_anterior;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_cargo' => 'int',
		'id_funcao' => 'int',
		'versao' => 'string',
		'data' => 'string',
		'sumario' => 'int',
		'formacao_experiencia' => 'int',
		'condicoes_gerais_exercicio' => 'int',
		'codigo_internacional_CIUO88' => 'int',
		'notas' => 'int',
		'recursos_trabalho' => 'int',
		'atividades' => 'int',
		'responsabilidades' => 'int',
		'conhecimentos_habilidades' => 'int',
		'habilidades_basicas' => 'int',
		'habilidades_intermediarias' => 'int',
		'habilidades_avancadas' => 'int',
		'ambiente_trabalho' => 'int',
		'condicoes_trabalho' => 'int',
		'esforcos_fisicos' => 'int',
		'grau_autonomia' => 'int',
		'grau_complexidade' => 'int',
		'grau_iniciativa' => 'int',
		'competencias_tecnicas' => 'int',
		'competencias_comportamentais' => 'int',
		'tempo_experiencia' => 'int',
		'formacao_minima' => 'int',
		'formacao_plena' => 'int',
		'esforcos_mentais' => 'int',
		'grau_pressao' => 'int',
		'campo_livre1' => '?string',
		'campo_livre2' => '?string',
		'campo_livre3' => '?string',
		'campo_livre4' => '?string',
		'campo_livre5' => '?string',
		'id_versao_anterior' => '?int'
	];

}
