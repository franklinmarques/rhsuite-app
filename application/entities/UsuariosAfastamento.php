<?php

include_once APPPATH . 'entities/Entity.php';

class UsuariosAfastamento extends Entity
{
	protected $id;
	protected $id_usuario;
	protected $id_empresa;
	protected $data_afastamento;
	protected $motivo_afastamento;
	protected $motivo_afastamento_bck;
	protected $data_pericia_medica;
	protected $data_limite_beneficio;
	protected $data_retorno;
	protected $historico_afastamento;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'id_empresa' => 'int',
		'data_afastamento' => 'date',
		'motivo_afastamento' => '?int',
		'motivo_afastamento_bck' => '?string',
		'data_pericia_medica' => '?date',
		'data_limite_beneficio' => '?date',
		'data_retorno' => '?date',
		'historico_afastamento' => '?string'
	];

}
