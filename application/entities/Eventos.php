<?php

include_once APPPATH . 'entities/Entity.php';

class Eventos extends Entity
{
	protected $id;
	protected $date_from;
	protected $date_to;
	protected $type;
	protected $title;
	protected $description;
	protected $link;
	protected $color;
	protected $status;
	protected $usuario;
	protected $usuario_referenciado;

	protected $casts = [
		'id' => 'int',
		'date_from' => 'datetime',
		'date_to' => 'datetime',
		'type' => 'int',
		'title' => 'string',
		'description' => 'string',
		'link' => '?string',
		'color' => '?string',
		'status' => 'int',
		'usuario' => '?int',
		'usuario_referenciado' => '?int'
	];

}
