<?php

include_once APPPATH . 'entities/Entity.php';

class EmtuAlocacaoFeriados extends Entity
{
	protected $id;
	protected $id_alocacao;
	protected $data;
	protected $status;
	protected $qtde_novos_processos;
	protected $qtde_analistas;
	protected $qtde_processos_tratados_dia;
	protected $qtde_pagamentos;

	protected $casts = [
		'id' => 'int',
		'id_alocacao' => 'int',
		'data' => 'date',
		'status' => 'string',
		'qtde_novos_processos' => '?int',
		'qtde_analistas' => '?int',
		'qtde_processos_tratados_dia' => '?int',
		'qtde_pagamentos' => '?int'
	];

}
