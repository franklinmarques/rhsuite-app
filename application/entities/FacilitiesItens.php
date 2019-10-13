<?php

include_once APPPATH . 'entities/Entity.php';

class FacilitiesItens extends Entity
{
	protected $id;
	protected $id_sala;
	protected $ativo;
	protected $nome;
	protected $codigo;
	protected $tipo;
	protected $data_entrada_operacao;
	protected $anos_duracao;
	protected $periodicidade_vistoria;
	protected $mes_vistoria_jan;
	protected $mes_vistoria_fev;
	protected $mes_vistoria_mar;
	protected $mes_vistoria_abr;
	protected $mes_vistoria_mai;
	protected $mes_vistoria_jun;
	protected $mes_vistoria_jul;
	protected $mes_vistoria_ago;
	protected $mes_vistoria_set;
	protected $mes_vistoria_out;
	protected $mes_vistoria_nov;
	protected $mes_vistoria_dez;
	protected $periodicidade_manutencao;
	protected $mes_manutencao_jan;
	protected $mes_manutencao_fev;
	protected $mes_manutencao_mar;
	protected $mes_manutencao_abr;
	protected $mes_manutencao_mai;
	protected $mes_manutencao_jun;
	protected $mes_manutencao_jul;
	protected $mes_manutencao_ago;
	protected $mes_manutencao_set;
	protected $mes_manutencao_out;
	protected $mes_manutencao_nov;
	protected $mes_manutencao_dez;

	protected $casts = [
		'id' => 'int',
		'id_sala' => 'int',
		'ativo' => 'int',
		'nome' => 'string',
		'codigo' => '?string',
		'tipo' => '?string',
		'data_entrada_operacao' => '?date',
		'anos_duracao' => '?int',
		'periodicidade_vistoria' => '?string',
		'mes_vistoria_jan' => '?int',
		'mes_vistoria_fev' => '?int',
		'mes_vistoria_mar' => '?int',
		'mes_vistoria_abr' => '?int',
		'mes_vistoria_mai' => '?int',
		'mes_vistoria_jun' => '?int',
		'mes_vistoria_jul' => '?int',
		'mes_vistoria_ago' => '?int',
		'mes_vistoria_set' => '?int',
		'mes_vistoria_out' => '?int',
		'mes_vistoria_nov' => '?int',
		'mes_vistoria_dez' => '?int',
		'periodicidade_manutencao' => '?string',
		'mes_manutencao_jan' => '?int',
		'mes_manutencao_fev' => '?int',
		'mes_manutencao_mar' => '?int',
		'mes_manutencao_abr' => '?int',
		'mes_manutencao_mai' => '?int',
		'mes_manutencao_jun' => '?int',
		'mes_manutencao_jul' => '?int',
		'mes_manutencao_ago' => '?int',
		'mes_manutencao_set' => '?int',
		'mes_manutencao_out' => '?int',
		'mes_manutencao_nov' => '?int',
		'mes_manutencao_dez' => '?int'
	];

}
