<?php

include_once APPPATH . 'entities/Entity.php';

class CdEscolas extends Entity
{
	protected $id;
	protected $nome;
	protected $id_diretoria;
	protected $endereco;
	protected $numero;
	protected $complemento;
	protected $bairro;
	protected $municipio;
	protected $telefone;
	protected $telefone_contato;
	protected $email;
	protected $cep;
	protected $periodo_manha;
	protected $periodo_tarde;
	protected $periodo_noite;

	protected $casts = [
		'id' => 'int',
		'nome' => 'string',
		'id_diretoria' => 'int',
		'endereco' => '?string',
		'numero' => '?int',
		'complemento' => '?string',
		'bairro' => '?string',
		'municipio' => 'string',
		'telefone' => '?string',
		'telefone_contato' => '?string',
		'email' => '?string',
		'cep' => '?string',
		'periodo_manha' => '?int',
		'periodo_tarde' => '?int',
		'periodo_noite' => '?int'
	];

}
