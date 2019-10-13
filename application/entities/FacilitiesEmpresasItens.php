<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesEmpresasItens extends Entity
{
	protected $id;
	protected $id_facility_empresa;
	protected $nome;
	protected $ativo;

	protected $casts = [
		'id' => 'int',
		'id_facility_empresa' => '?int',
		'nome' => 'string',
		'ativo' => 'int'
	];

}
