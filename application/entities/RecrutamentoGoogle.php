<?php

include_once APPPATH . 'entities/Entity.php';

class RecrutamentoGoogle extends Entity
{
	protected $id;
	protected $cliente;
	protected $cargo;
	protected $cidade;
	protected $nome;
	protected $data_nascimento;
	protected $deficiencia;
	protected $telefone;
	protected $email;
	protected $fonte_contratacao;
	protected $status;
	protected $data_entrevista_rh;
	protected $resultado_entrevista_rh;
	protected $data_entrevista_cliente;
	protected $resultado_entrevista_cliente;
	protected $observacoes;

	protected $casts = [
		'id' => 'int',
		'cliente' => '?string',
		'cargo' => '?string',
		'cidade' => '?string',
		'nome' => 'string',
		'data_nascimento' => '?date',
		'deficiencia' => '?string',
		'telefone' => '?string',
		'email' => '?string',
		'fonte_contratacao' => '?string',
		'status' => '?string',
		'data_entrevista_rh' => '?string',
		'resultado_entrevista_rh' => '?string',
		'data_entrevista_cliente' => '?string',
		'resultado_entrevista_cliente' => '?string',
		'observacoes' => '?string'
	];

}
