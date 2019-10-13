<?php

include_once APPPATH . 'entities/Entity.php';

class AtividadesScheduler extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_usuario;
	protected $atividade;
	protected $dia;
	protected $semana;
	protected $mes;
	protected $objetivos;
	protected $data_cadastro;
	protected $data_limite;
	protected $envolvidos;
	protected $observacoes;
	protected $processo_roteiro;
	protected $documento_1;
	protected $documento_2;
	protected $documento_3;
	protected $lembrar;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_usuario' => '?int',
		'atividade' => 'string',
		'dia' => '?int',
		'semana' => '?int',
		'mes' => '?int',
		'objetivos' => 'string',
		'data_cadastro' => 'date',
		'data_limite' => '?string',
		'envolvidos' => 'string',
		'observacoes' => '?string',
		'processo_roteiro' => '?string',
		'documento_1' => '?string',
		'documento_2' => '?string',
		'documento_3' => '?string',
		'lembrar' => 'int'
	];

	//==========================================================================
	public function setDataCadastro($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['data_cadastro'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

}
