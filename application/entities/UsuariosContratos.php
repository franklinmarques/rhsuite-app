<?php

include_once APPPATH . 'entities/Entity.php';

class UsuariosContratos extends Entity
{
	protected $id;
	protected $id_usuario;
	protected $data_assinatura;
	protected $id_depto;
	protected $id_area;
	protected $id_setor;
	protected $id_cargo;
	protected $id_funcao;
	protected $contrato;
	protected $valor_posto;
	protected $conversor_dia;
	protected $conversor_hora;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'data_assinatura' => 'date',
		'id_depto' => 'int',
		'id_area' => 'int',
		'id_setor' => 'int',
		'id_cargo' => 'int',
		'id_funcao' => 'int',
		'contrato' => '?string',
		'valor_posto' => 'float',
		'conversor_dia' => '?float',
		'conversor_hora' => '?float'
	];

}
