<?php

include_once APPPATH . 'entities/Entity.php';

class EiFaturamentoConsolidado extends Entity
{
	protected $id;
	protected $id_alocacao;
	protected $cargo;
	protected $funcao;
	protected $valor_hora_mes1;
	protected $valor_hora_mes2;
	protected $valor_hora_mes3;
	protected $valor_hora_mes4;
	protected $valor_hora_mes5;
	protected $valor_hora_mes6;
	protected $valor_hora_mes7;
	protected $total_horas_mes1;
	protected $total_horas_mes2;
	protected $total_horas_mes3;
	protected $total_horas_mes4;
	protected $total_horas_mes5;
	protected $total_horas_mes6;
	protected $total_horas_mes7;
	protected $valor_faturado_mes1;
	protected $valor_faturado_mes2;
	protected $valor_faturado_mes3;
	protected $valor_faturado_mes4;
	protected $valor_faturado_mes5;
	protected $valor_faturado_mes6;
	protected $valor_faturado_mes7;
	protected $total_escolas;
	protected $total_alunos;
	protected $total_cuidadores;
	protected $total_horas_projetadas;
	protected $total_horas_realizadas;
	protected $receita_projetada;
	protected $receita_efetuada;
	protected $pagamentos_efetuados;
	protected $resultado;
	protected $resultado_percentual;

	protected $casts = [
		'id' => 'int',
		'id_alocacao' => 'int',
		'cargo' => 'string',
		'funcao' => 'string',
		'valor_hora_mes1' => 'float',
		'valor_hora_mes2' => 'float',
		'valor_hora_mes3' => 'float',
		'valor_hora_mes4' => 'float',
		'valor_hora_mes5' => 'float',
		'valor_hora_mes6' => 'float',
		'valor_hora_mes7' => 'float',
		'total_horas_mes1' => 'string',
		'total_horas_mes2' => 'string',
		'total_horas_mes3' => 'string',
		'total_horas_mes4' => 'string',
		'total_horas_mes5' => 'string',
		'total_horas_mes6' => 'string',
		'total_horas_mes7' => 'string',
		'valor_faturado_mes1' => '?float',
		'valor_faturado_mes2' => '?float',
		'valor_faturado_mes3' => '?float',
		'valor_faturado_mes4' => '?float',
		'valor_faturado_mes5' => '?float',
		'valor_faturado_mes6' => '?float',
		'valor_faturado_mes7' => '?float',
		'total_escolas' => '?int',
		'total_alunos' => '?int',
		'total_cuidadores' => '?int',
		'total_horas_projetadas' => '?string',
		'total_horas_realizadas' => '?string',
		'receita_projetada' => '?float',
		'receita_efetuada' => '?float',
		'pagamentos_efetuados' => '?float',
		'resultado' => '?float',
		'resultado_percentual' => '?float'
	];

}
