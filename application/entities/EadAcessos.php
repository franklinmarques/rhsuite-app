<?php

include_once APPPATH . 'entities/Entity.php';

class EadAcessos extends Entity
{
	protected $id;
	protected $id_curso_usuario;
	protected $id_pagina;
	protected $data_acesso;
	protected $data_atualizacao;
	protected $tempo_estudo;
	protected $data_finalizacao;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_curso_usuario' => 'int',
		'id_pagina' => 'int',
		'data_acesso' => 'datetime',
		'data_atualizacao' => '?datetime',
		'tempo_estudo' => '?time',
		'data_finalizacao' => '?datetime',
		'status' => 'int'
	];

	//==========================================================================
	public function setDataAcesso($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['data_acesso'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

	//==========================================================================
	public function setDataAtualizacao($value = null)
	{
		if ($this->attributes['id']) {
			$this->attributes['data_atualizacao'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

}
