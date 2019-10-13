<?php

include_once APPPATH . 'entities/Entity.php';

class GestaoPessoalIndicadores extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $mes;
	protected $ano;
	protected $total_colaboradores_ativos;
	protected $total_colaboradores_admitidos;
	protected $total_colaboradores_demitidos;
	protected $total_colaboradores_justa_causa;
	protected $total_colaboradores_desligados;
	protected $total_demissoes_desligamentos;
	protected $total_acidentes;
	protected $total_maternidade;
	protected $total_aposentadoria;
	protected $total_doenca;
	protected $total_faltas_st;
	protected $total_faltas_cd;
	protected $total_faltas_gp;
	protected $total_faltas_cdh;
	protected $total_faltas_icom;
	protected $total_faltas_adm;
	protected $total_faltas_prj;
	protected $total_colaboradores;
	protected $total_atrasos_4_horas;
	protected $total_atrasos_8_horas;
	protected $total_faltas_1_dia;
	protected $total_faltas_2_dias;
	protected $total_faltas_3_dias;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'mes' => 'int',
		'ano' => 'int',
		'total_colaboradores_ativos' => '?int',
		'total_colaboradores_admitidos' => '?int',
		'total_colaboradores_demitidos' => '?int',
		'total_colaboradores_justa_causa' => '?int',
		'total_colaboradores_desligados' => '?int',
		'total_demissoes_desligamentos' => '?int',
		'total_acidentes' => '?int',
		'total_maternidade' => '?int',
		'total_aposentadoria' => '?int',
		'total_doenca' => '?int',
		'total_faltas_st' => '?int',
		'total_faltas_cd' => '?int',
		'total_faltas_gp' => '?int',
		'total_faltas_cdh' => '?int',
		'total_faltas_icom' => '?int',
		'total_faltas_adm' => '?int',
		'total_faltas_prj' => '?int',
		'total_colaboradores' => '?int',
		'total_atrasos_4_horas' => '?int',
		'total_atrasos_8_horas' => '?int',
		'total_faltas_1_dia' => '?int',
		'total_faltas_2_dias' => '?int',
		'total_faltas_3_dias' => '?int'
	];

}
