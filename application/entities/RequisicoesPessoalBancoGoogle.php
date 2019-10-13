<?php

include_once APPPATH . 'entities/Entity.php';

class RequisicoesPessoalBancoGoogle extends Entity
{
	protected $id;
	protected $id_requisicao;
	protected $cliente;
	protected $nome_candidato;
	protected $cargo;
	protected $cidade;
	protected $deficiencia;
	protected $telefone;
	protected $fonte_contratacao;
	protected $data_captacao;
	protected $data_entrevista_rh;
	protected $resultado-entrevista_rh;
    protected $data_entrevista_cliente;
    protected $resultado_entrevista_cliente;
    protected $status;
    protected $observacoes;

    protected $casts = [
		'id' => 'int',
		'id_requisicao' => 'int',
		'cliente' => 'int',
		'nome_candidato' => 'string',
		'cargo' => 'int',
		'cidade' => 'int',
		'deficiencia' => '?int',
		'telefone' => '?string',
		'fonte_contratacao' => '?int',
		'data_captacao' => '?date',
		'data_entrevista_rh' => '?date',
		'resultado-entrevista_rh' => '?int',
		'data_entrevista_cliente' => '?date',
		'resultado_entrevista_cliente' => '?int',
		'status' => '?int',
		'observacoes' => '?string'
	];

}
