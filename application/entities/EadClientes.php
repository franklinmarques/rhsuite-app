<?php

include_once APPPATH . 'entities/Entity.php';
include_once APPPATH . 'libraries/Auth.php';

class EadClientes extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $nome;
	protected $cliente;
	protected $email;
	protected $senha;
	protected $token;
	protected $foto;
	protected $data_cadastro;
	protected $data_edicao;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'nome' => 'string',
		'cliente' => 'string',
		'email' => 'string',
		'senha' => 'string',
		'token' => 'string',
		'foto' => '?string',
		'data_cadastro' => 'datetime',
		'data_edicao' => '?datetime',
		'status' => 'int'
	];

	//==========================================================================
	public function setSenha(string $humanPassword = '')
	{
		if (strlen($humanPassword) > 0) {
			$auth = new Auth();

			$this->attributes['senha'] = $auth->encryptPassword($humanPassword);
		} else {
			$this->attributes['senha'] = null;
		}

		return $this;
	}

	//==========================================================================
	public function setToken($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['token'] = uniqid();
		}

		return $this;
	}

	//==========================================================================
	public function setDataCadastro($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['data_cadastro'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

	//==========================================================================
	public function setDataEdicao($value = null)
	{
		if ($this->attributes['id']) {
			$this->attributes['data_edicao'] = date('Y-m-d H:i:s');
		}

		return $this;
	}

}
