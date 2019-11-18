<?php

include_once APPPATH . 'entities/Entity.php';

class EadClientesTreinamentos extends Entity
{
	protected $id;
	protected $id_usuario;
	protected $id_curso;
	protected $data_cadastro;
	protected $data_inicio;
	protected $data_maxima;
	protected $colaboradores_maximo;
	protected $nota_aprovacao;
	protected $tipo_treinamento;
	protected $local_treinamento;
	protected $nome;
	protected $carga_horaria_presencial;
	protected $avaliacao_presencial;
	protected $nome_fornecedor;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'id_curso' => '?int',
		'data_cadastro' => 'datetime',
		'data_inicio' => '?date',
		'data_maxima' => '?date',
		'colaboradores_maximo' => '?int',
		'nota_aprovacao' => '?int',
		'tipo_treinamento' => '?string',
		'local_treinamento' => '?string',
		'nome' => '?string',
		'carga_horaria_presencial' => '?time',
		'avaliacao_presencial' => '?int',
		'nome_fornecedor' => '?string'
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
