<?php

include_once APPPATH . 'entities/Entity.php';

class EadCursos extends Entity
{
	protected $id;
	protected $nome;
	protected $id_empresa;
	protected $publico;
	protected $gratuito;
	protected $descricao;
	protected $data_cadastro;
	protected $data_editado;
	protected $horas_duracao;
	protected $objetivos;
	protected $competencias_genericas;
	protected $competencias_especificas;
	protected $competencias_comportamentais;
	protected $categoria;
	protected $id_categoria;
	protected $area_conhecimento;
	protected $id_area;
	protected $consultor;
	protected $foto_consultor;
	protected $curriculo;
	protected $foto_treinamento;
	protected $pre_requisitos;
	protected $progressao_linear;
	protected $status;
	protected $id_copia;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_empresa' => 'int',
		'publico' => 'int',
		'gratuito' => 'int',
		'descricao' => '?string',
		'data_cadastro' => 'datetime',
		'data_editado' => '?datetime',
		'horas_duracao' => 'int',
		'objetivos' => '?string',
		'competencias_genericas' => '?string',
		'competencias_especificas' => '?string',
		'competencias_comportamentais' => '?string',
		'categoria' => '?string',
		'id_categoria' => '?int',
		'area_conhecimento' => '?string',
		'id_area' => '?int',
		'consultor' => '?string',
		'foto_consultor' => '?string',
		'curriculo' => '?string',
		'foto_treinamento' => '?string',
		'pre_requisitos' => '?string',
		'progressao_linear' => 'int',
		'status' => 'int',
		'id_copia' => '?int'
	];

	//==========================================================================
	public function setDataCadastro($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['data_cadastro'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

	//==========================================================================
	public function setDataEditado($value = null)
	{
		if ($this->attributes['id']) {
			$this->attributes['data_editado'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

}
