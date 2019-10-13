<?php

include_once APPPATH . 'entities/Entity.php';

class IcomAlocadosFeedback extends Entity
{
	protected $id;
	protected $id_alocado;
	protected $id_usuario_orientador;
	protected $nome_usuario_orientador;
	protected $data;
	protected $descricao;
	protected $resultado;

	protected $casts = [
		'id' => 'int',
		'id_alocado' => 'int',
		'id_usuario_orientador' => '?int',
		'nome_usuario_orientador' => 'string',
		'data' => 'date',
		'descricao' => '?string',
		'resultado' => '?string'
	];

}
