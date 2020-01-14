<?php

include_once APPPATH . 'entities/Entity.php';

class IcomClientes extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $nome;
	protected $cnpj;
	protected $endereco;
	protected $observacoes;
	protected $contato_principal;
	protected $telefone_contato_principal;
	protected $email_contato_principal;
	protected $cargo_contato_principal;
	protected $contato_secundario;
	protected $telefone_contato_secundario;
	protected $email_contato_secundario;
	protected $cargo_contato_secundario;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'nome' => 'string',
		'cnpj' => '?string',
		'endereco' => '?string',
		'observacoes' => '?string',
		'contato_principal' => '?string',
		'telefone_contato_principal' => '?string',
		'email_contato_principal' => '?string',
		'cargo_contato_principal' => '?string',
		'contato_secundario' => '?string',
		'telefone_contato_secundario' => '?string',
		'email_contato_secundario' => '?string',
		'cargo_contato_secundario' => '?string'
	];

}
