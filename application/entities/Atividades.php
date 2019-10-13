<?php

include_once APPPATH . 'entities/Entity.php';

class Atividades extends Entity
{
	protected $id;
	protected $id_usuario;
	protected $tipo;
	protected $prioridade;
	protected $atividade;
	protected $data_cadastro;
	protected $data_limite;
	protected $data_lembrete;
	protected $data_fechamento;
	protected $status;
	protected $observacoes;
	protected $id_mae;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'tipo' => 'string',
		'prioridade' => 'int',
		'atividade' => 'string',
		'data_cadastro' => 'datetime',
		'data_limite' => 'datetime',
		'data_lembrete' => 'date',
		'data_fechamento' => '?datetime',
		'status' => 'int',
		'observacoes' => '?string',
		'id_mae' => '?int'
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
