<?php

include_once APPPATH . 'entities/Entity.php';

class ArquivosTemp extends Entity
{
	protected $id;
	protected $arquivo;
	protected $usuario;

	protected $casts = [
		'id' => 'int',
		'arquivo' => '?string',
		'usuario' => '?int'
	];

	//==========================================================================
	public function setUsuario($value = null)
	{
		if (is_null($value)) {
			$ci = &get_instance();
			$value = $ci->session->userdata('id');
		}

		if (strlen($value) > 0) {
			$this->attributes['usuario'] = $value;
		} else {
			$this->attributes['usuario'] = null;
		}

		return $this;
	}

}
