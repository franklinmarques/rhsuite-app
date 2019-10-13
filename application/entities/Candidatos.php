<?php

include_once APPPATH . 'entities/Entity.php';
include_once APPPATH . 'libraries/Auth.php';

class Candidatos extends Entity
{
	protected $id;
	protected $empresa;
	protected $nome;
	protected $data_nascimento;
	protected $sexo;
	protected $estado_civil;
	protected $nome_mae;
	protected $nome_pai;
	protected $cpf;
	protected $rg;
	protected $pis;
	protected $logradouro;
	protected $numero;
	protected $complemento;
	protected $bairro;
	protected $cidade;
	protected $estado;
	protected $cep;
	protected $escolaridade;
	protected $deficiencia;
	protected $foto;
	protected $telefone;
	protected $email;
	protected $senha;
	protected $token;
	protected $data_inscricao;
	protected $fonte_contratacao;
	protected $data_edicao;
	protected $nivel_acesso;
	protected $url;
	protected $arquivo_curriculo;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'empresa' => 'int',
		'nome' => 'string',
		'data_nascimento' => '?date',
		'sexo' => '?string',
		'estado_civil' => '?int',
		'nome_mae' => '?string',
		'nome_pai' => '?string',
		'cpf' => '?string',
		'rg' => '?string',
		'pis' => '?string',
		'logradouro' => '?string',
		'numero' => '?int',
		'complemento' => '?string',
		'bairro' => '?string',
		'cidade' => '?int',
		'estado' => '?int',
		'cep' => '?string',
		'escolaridade' => '?int',
		'deficiencia' => '?int',
		'foto' => '?string',
		'telefone' => 'string',
		'email' => 'string',
		'senha' => 'string',
		'token' => 'string',
		'data_inscricao' => '?datetime',
		'fonte_contratacao' => '?string',
		'data_edicao' => '?datetime',
		'nivel_acesso' => 'string',
		'url' => '?string',
		'arquivo_curriculo' => '?string',
		'status' => 'string'
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

}
