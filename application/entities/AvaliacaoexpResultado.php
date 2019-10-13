<?php

include_once APPPATH . 'entities/Entity.php';

class AvaliacaoexpResultado extends Entity
{
	protected $id;
	protected $id_avaliador;
	protected $id_pergunta;
	protected $id_alternativa;
	protected $resposta;
	protected $data_avaliacao;

	protected $casts = [
		'id' => 'int',
		'id_avaliador' => 'int',
		'id_pergunta' => 'int',
		'id_alternativa' => '?int',
		'resposta' => '?string',
		'data_avaliacao' => 'datetime'
	];

	//==========================================================================
	public function setDataAvaliacao($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['data_avaliacao'] = date('Y-m-d H:i:s');
		} else {
			if (strlen($value) > 0) {
				$this->attributes['data_avaliacao'] = $value;
			} else {
				$this->attributes['data_avaliacao'] = null;
			}
		}

		return $this;
	}

}
