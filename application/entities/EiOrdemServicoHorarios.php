<?php

include_once APPPATH . 'entities/Entity.php';

class EiOrdemServicoHorarios extends Entity
{
	protected $id;
	protected $id_os_profissional;
	protected $id_funcao;
	protected $id_os_profissional_sub1;
	protected $id_funcao_sub1;
	protected $data_substituicao1;
	protected $id_os_profissional_sub2;
	protected $id_funcao_sub2;
	protected $data_substituicao2;
	protected $dia_semana;
	protected $periodo;
	protected $horario_inicio;
	protected $horario_termino;
	protected $total_dias_mes1;
	protected $total_dias_mes2;
	protected $total_dias_mes3;
	protected $total_dias_mes4;
	protected $total_dias_mes5;
	protected $total_dias_mes6;
	protected $valor_hora;
	protected $horas_diarias;
	protected $qtde_dias;
	protected $horas_semanais;
	protected $qtde_semanas;
	protected $horas_mensais;
	protected $horas_semestre;
	protected $valor_hora_mensal;
	protected $valor_hora_operacional;
	protected $horas_mensais_custo;
	protected $data_inicio_contrato;
	protected $data_termino_contrato;
	protected $desconto_mensal_1;
	protected $desconto_mensal_2;
	protected $desconto_mensal_3;
	protected $desconto_mensal_4;
	protected $desconto_mensal_5;
	protected $desconto_mensal_6;
	protected $valor_mensal_1;
	protected $valor_mensal_2;
	protected $valor_mensal_3;
	protected $valor_mensal_4;
	protected $valor_mensal_5;
	protected $valor_mensal_6;

	protected $casts = [
		'id' => 'int',
		'id_os_profissional' => 'int',
		'id_funcao' => '?int',
		'id_os_profissional_sub1' => '?int',
		'id_funcao_sub1' => '?int',
		'data_substituicao1' => '?date',
		'id_os_profissional_sub2' => '?int',
		'id_funcao_sub2' => '?int',
		'data_substituicao2' => '?date',
		'dia_semana' => '?int',
		'periodo' => '?int',
		'horario_inicio' => '?time',
		'horario_termino' => '?time',
		'total_dias_mes1' => '?int',
		'total_dias_mes2' => '?int',
		'total_dias_mes3' => '?int',
		'total_dias_mes4' => '?int',
		'total_dias_mes5' => '?int',
		'total_dias_mes6' => '?int',
		'valor_hora' => '?float',
		'horas_diarias' => '?float',
		'qtde_dias' => '?float',
		'horas_semanais' => '?float',
		'qtde_semanas' => '?int',
		'horas_mensais' => '?float',
		'horas_semestre' => '?float',
		'valor_hora_mensal' => '?float',
		'valor_hora_operacional' => '?float',
		'horas_mensais_custo' => '?time',
		'data_inicio_contrato' => '?date',
		'data_termino_contrato' => '?date',
		'desconto_mensal_1' => '?float',
		'desconto_mensal_2' => '?float',
		'desconto_mensal_3' => '?float',
		'desconto_mensal_4' => '?float',
		'desconto_mensal_5' => '?float',
		'desconto_mensal_6' => '?float',
		'valor_mensal_1' => '?float',
		'valor_mensal_2' => '?float',
		'valor_mensal_3' => '?float',
		'valor_mensal_4' => '?float',
		'valor_mensal_5' => '?float',
		'valor_mensal_6' => '?float'
	];

}
