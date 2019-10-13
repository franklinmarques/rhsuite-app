<?php

include_once APPPATH . 'entities/Entity.php';

class LogUsuarios extends Entity
{
	protected $id;
	protected $usuario;
	protected $tipo;
	protected $data_acesso;
	protected $data_atualizacao;
	protected $data_saida;
	protected $endereco_ip;
	protected $agente_usuario;
	protected $id_sessao;

	private $_CI;

	protected $casts = [
		'id' => 'int',
		'usuario' => 'int',
		'tipo' => '?string',
		'data_acesso' => 'datetime',
		'data_atualizacao' => '?datetime',
		'data_saida' => '?datetime',
		'endereco_ip' => '?string',
		'agente_usuario' => '?string',
		'id_sessao' => '?string'
	];

	//==========================================================================
	public function __construct(array $data = null)
	{
		$this->_CI = &get_instance();
		parent::__construct($data);
	}

	//==========================================================================
	public function setUsuario($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['usuario'] = $this->_CI->session->userdata('id');
		}

		return $this;
	}

	//==========================================================================
	public function setTipo($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['usuario'] = $this->_CI->session->userdata('tipo');
		}

		return $this;
	}

	//==========================================================================
	public function setDataAcesso($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['data_acesso'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

	//==========================================================================
	public function setDataAtualizacao($value = null)
	{
		if ($this->attributes['id']) {
			$this->attributes['data_atualizacao'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

	//==========================================================================
	public function setEnderecoIp($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['endereco_ip'] = $this->_CI->input->ip_address();
		}

		return $this;
	}

	//==========================================================================
	public function setAgenteUsuario($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['agente_usuario'] = $this->_CI->input->user_agent();
		}

		return $this;
	}

	//==========================================================================
	public function setIdSessao($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['id_sessao'] = session_id();
		}

		return $this;
	}

}
