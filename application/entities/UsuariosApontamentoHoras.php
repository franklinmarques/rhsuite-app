<?php

include_once APPPATH . 'entities/Entity.php';

class UsuariosApontamentoHoras extends Entity
{
	protected $id;
	protected $idUsuario;
	protected $dataHora;
	protected $turnoEvento;
	protected $modoCadastramento;
	protected $idDepto;
	protected $idArea;
	protected $idSetor;
	protected $jsutificativa;
	protected $aceiteJustificativa;
	protected $dataAceite;
	protected $respostaAceite;
	protected $idUsuarioAceite;

	protected $casts = [
		'id' => 'int',
		'id_usuario' => 'int',
		'data_hora' => 'datetime',
		'turno_evento' => 'string',
		'modo_cadastramento' => 'string',
		'id_depto' => '?int',
		'id_area' => '?int',
		'id_setor' => '?int',
		'justificativa' => '?string',
		'aceite_justificativa' => '?int',
		'data_aceite' => '?datetime',
		'observacoes_aceite' => '?string',
		'id_usuario_aceite' => '?int'
	];

	public function setAceiteJustificativa($value)
	{
		if (strlen($value) == 0) {
			$value = null;
		} else {
			$value = (int)$value;
		}
		$this->attributes['aceite_justificativa'] = $value;
		return $this;
	}

}
