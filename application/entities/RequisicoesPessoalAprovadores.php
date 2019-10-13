<?php

include_once APPPATH . 'entities/Entity.php';

class RequisicoesPessoalAprovadores extends Entity
{
	protected $id_usuario;

	protected $casts = [
		'id_usuario' => 'int'
	];

}
