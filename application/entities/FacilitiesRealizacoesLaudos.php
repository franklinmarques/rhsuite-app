<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesRealizacoesLaudos extends Entity
{
	protected $id;
	protected $id_realizacao;
	protected $id_item;
	protected $arquivo;
	protected $tipo_mime;
	protected $data_cadastro;
	protected $local_armazem;
	protected $sala_box;
	protected $arquivo_fisico;
	protected $pasta_caixa;
	protected $codigo_localizador;

	protected $casts = [
		'id' => 'int',
		'id_realizacao' => 'int',
		'id_item' => 'int',
		'arquivo' => 'string',
		'tipo_mime' => 'string',
		'data_cadastro' => 'datetime',
		'local_armazem' => '?string',
		'sala_box' => '?string',
		'arquivo_fisico' => '?string',
		'pasta_caixa' => '?string',
		'codigo_localizador' => '?string'
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
