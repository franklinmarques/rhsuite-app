<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoMedicoesResultado extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $id_usuario;
	protected $id_crono_analise;
	protected $id_executor;
	protected $id_processo;
	protected $id_atividade;
	protected $id_etapa;
	protected $grau_complexidade;
	protected $tamanho_item;
	protected $soma_menor;
	protected $soma_media;
	protected $soma_maior;
	protected $mao_obra_menor;
	protected $mao_obra_media;
	protected $mao_obra_maior;
	protected $data_cadastro;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'id_usuario' => 'int',
		'id_crono_analise' => 'int',
		'id_executor' => 'int',
		'id_processo' => '?int',
		'id_atividade' => '?int',
		'id_etapa' => '?int',
		'grau_complexidade' => '?int',
		'tamanho_item' => '?int',
		'soma_menor' => 'float',
		'soma_media' => 'float',
		'soma_maior' => 'float',
		'mao_obra_menor' => 'float',
		'mao_obra_media' => 'float',
		'mao_obra_maior' => 'float',
		'data_cadastro' => 'datetime'
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
