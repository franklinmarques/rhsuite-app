<?php

include_once APPPATH . 'entities/Entity.php';

class IcomContratos extends Entity
{
	protected $codigo;
	protected $id_empresa;
	protected $codigo_proposta;
	protected $centro_custo;
	protected $data_vencimento;
	protected $status_ativo;
	protected $arquivo;

	protected $casts = [
		'codigo' => 'int',
		'id_empresa' => 'int',
		'codigo_proposta' => 'int',
		'centro_custo' => '?string',
		'data_vencimento' => 'date',
		'status_ativo' => 'int',
		'arquivo' => '?string'
	];

}
