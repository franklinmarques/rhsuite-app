<?php

include_once APPPATH . 'entities/Entity.php';

class CandidatosVagas extends Entity
{
	protected $id;
	protected $id_candidato;
	protected $codigo_vaga;
	protected $data_cadastro;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_candidato' => 'int',
		'codigo_vaga' => 'int',
		'data_cadastro' => 'datetime',
		'status' => '?string'
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
