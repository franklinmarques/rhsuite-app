<?php

include_once APPPATH . 'entities/Entity.php';

class EiAlocadosHorarios extends Entity
{
	protected $id;
	protected $id_alocado;
	protected $id_os_horario;
	protected $cargo;
	protected $funcao;
	protected $dia_semana;
	protected $periodo;
	protected $horario_inicio_mes1;
	protected $horario_inicio_mes2;
	protected $horario_inicio_mes3;
	protected $horario_inicio_mes4;
	protected $horario_inicio_mes5;
	protected $horario_inicio_mes6;
	protected $horario_inicio_mes7;
	protected $horario_termino_mes1;
	protected $horario_termino_mes2;
	protected $horario_termino_mes3;
	protected $horario_termino_mes4;
	protected $horario_termino_mes5;
	protected $horario_termino_mes6;
	protected $horario_termino_mes7;
	protected $total_horas_mes1;
	protected $total_horas_mes2;
	protected $total_horas_mes3;
	protected $total_horas_mes4;
	protected $total_horas_mes5;
	protected $total_horas_mes6;
	protected $total_horas_mes7;
	protected $total_semanas_mes1;
	protected $total_semanas_mes2;
	protected $total_semanas_mes3;
	protected $total_semanas_mes4;
	protected $total_semanas_mes5;
	protected $total_semanas_mes6;
	protected $total_semanas_mes7;
	protected $desconto_mes1;
	protected $desconto_mes2;
	protected $desconto_mes3;
	protected $desconto_mes4;
	protected $desconto_mes5;
	protected $desconto_mes6;
	protected $desconto_mes7;
	protected $endosso_mes1;
	protected $endosso_mes2;
	protected $endosso_mes3;
	protected $endosso_mes4;
	protected $endosso_mes5;
	protected $endosso_mes6;
	protected $endosso_mes7;
	protected $endosso_sub1;
	protected $endosso_sub2;
	protected $total_mes1;
	protected $total_mes2;
	protected $total_mes3;
	protected $total_mes4;
	protected $total_mes5;
	protected $total_mes6;
	protected $total_mes7;
	protected $total_endossado_mes1;
	protected $total_endossado_mes2;
	protected $total_endossado_mes3;
	protected $total_endossado_mes4;
	protected $total_endossado_mes5;
	protected $total_endossado_mes6;
	protected $total_endossado_mes7;
	protected $total_endossado_sub1;
	protected $total_endossado_sub2;
	protected $id_cuidador_sub1;
	protected $cargo_sub1;
	protected $funcao_sub1;
	protected $data_substituicao1;
	protected $total_semanas_sub1;
	protected $desconto_sub1;
	protected $total_sub1;
	protected $id_cuidador_sub2;
	protected $cargo_sub2;
	protected $funcao_sub2;
	protected $data_substituicao2;
	protected $total_semanas_sub2;
	protected $desconto_sub2;
	protected $total_sub2;
	protected $data_inicio_contrato;
	protected $data_termino_contrato;
	protected $valor_hora_operacional;
	protected $horas_mensais_custo;
	protected $valor_hora_funcao;
	protected $data_inicio_real;
	protected $data_termino_real;

	protected $casts = [
		'id' => 'int',
		'id_alocado' => 'int',
		'id_os_horario' => '?int',
		'cargo' => '?string',
		'funcao' => '?string',
		'dia_semana' => 'int',
		'periodo' => '?int',
		'horario_inicio_mes1' => '?time',
		'horario_inicio_mes2' => '?time',
		'horario_inicio_mes3' => '?time',
		'horario_inicio_mes4' => '?time',
		'horario_inicio_mes5' => '?time',
		'horario_inicio_mes6' => '?time',
		'horario_inicio_mes7' => '?time',
		'horario_termino_mes1' => '?time',
		'horario_termino_mes2' => '?time',
		'horario_termino_mes3' => '?time',
		'horario_termino_mes4' => '?time',
		'horario_termino_mes5' => '?time',
		'horario_termino_mes6' => '?time',
		'horario_termino_mes7' => '?time',
		'total_horas_mes1' => '?time',
		'total_horas_mes2' => '?time',
		'total_horas_mes3' => '?time',
		'total_horas_mes4' => '?time',
		'total_horas_mes5' => '?time',
		'total_horas_mes6' => '?time',
		'total_horas_mes7' => '?time',
		'total_semanas_mes1' => 'int',
		'total_semanas_mes2' => 'int',
		'total_semanas_mes3' => 'int',
		'total_semanas_mes4' => 'int',
		'total_semanas_mes5' => 'int',
		'total_semanas_mes6' => 'int',
		'total_semanas_mes7' => 'int',
		'desconto_mes1' => '?float',
		'desconto_mes2' => '?float',
		'desconto_mes3' => '?float',
		'desconto_mes4' => '?float',
		'desconto_mes5' => '?float',
		'desconto_mes6' => '?float',
		'desconto_mes7' => '?float',
		'endosso_mes1' => '?float',
		'endosso_mes2' => '?float',
		'endosso_mes3' => '?float',
		'endosso_mes4' => '?float',
		'endosso_mes5' => '?float',
		'endosso_mes6' => '?float',
		'endosso_mes7' => '?float',
		'endosso_sub1' => '?float',
		'endosso_sub2' => '?float',
		'total_mes1' => '?time',
		'total_mes2' => '?time',
		'total_mes3' => '?time',
		'total_mes4' => '?time',
		'total_mes5' => '?time',
		'total_mes6' => '?time',
		'total_mes7' => '?time',
		'total_endossado_mes1' => '?time',
		'total_endossado_mes2' => '?time',
		'total_endossado_mes3' => '?time',
		'total_endossado_mes4' => '?time',
		'total_endossado_mes5' => '?time',
		'total_endossado_mes6' => '?time',
		'total_endossado_mes7' => '?time',
		'total_endossado_sub1' => '?time',
		'total_endossado_sub2' => '?time',
		'id_cuidador_sub1' => '?int',
		'cargo_sub1' => '?string',
		'funcao_sub1' => '?string',
		'data_substituicao1' => '?date',
		'total_semanas_sub1' => '?int',
		'desconto_sub1' => '?float',
		'total_sub1' => '?time',
		'id_cuidador_sub2' => '?int',
		'cargo_sub2' => '?string',
		'funcao_sub2' => '?string',
		'data_substituicao2' => '?date',
		'total_semanas_sub2' => '?int',
		'desconto_sub2' => '?float',
		'total_sub2' => '?time',
		'data_inicio_contrato' => '?date',
		'data_termino_contrato' => '?date',
		'valor_hora_operacional' => '?float',
		'horas_mensais_custo' => '?time',
		'valor_hora_funcao' => '?float',
		'data_inicio_real' => '?date',
		'data_termino_real' => '?date'
	];

}
