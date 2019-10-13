<?php

include_once APPPATH . 'entities/Entity.php';

class EiAlocados extends Entity
{
	protected $id;
	protected $id_alocacao_escola;
	protected $id_os_profissional;
	protected $id_cuidador;
	protected $cuidador;
	protected $valor_hora;
	protected $valor_hora_operacional;
	protected $valor_hora_pagamento;
	protected $horas_diarias;
	protected $horas_semanais;
	protected $qtde_dias;
	protected $horas_semestre;
	protected $total_dias_letivos;
	protected $data_inicio_contrato;
	protected $data_termino_contrato;
	protected $horas_mensais_custo;
	protected $valor_total;

	protected $casts = [
		'id' => 'int',
		'id_alocacao_escola' => 'int',
		'id_os_profissional' => '?int',
		'id_cuidador' => '?int',
		'cuidador' => '?string',
		'valor_hora' => '?float',
		'valor_hora_operacional' => '?float',
		'valor_hora_pagamento' => '?float',
		'horas_diarias' => '?float',
		'horas_semanais' => '?float',
		'qtde_dias' => '?float',
		'horas_semestre' => '?float',
		'total_dias_letivos' => 'int',
		'data_inicio_contrato' => '?date',
		'data_termino_contrato' => '?date',
		'horas_mensais_custo' => '?time',
		'valor_total' => '?float'
	];

}
