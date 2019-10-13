<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gestao_pessoal_indicadores_model extends MY_Model
{
	protected static $table = 'gestao_pessoal_indicadores';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'mes' => 'required|integer|max_length[2]',
		'ano' => 'required|is_natural_no_zero|max_length[4]',
		'total_colaboradores_ativos' => 'integer|max_length[11]',
		'total_colaboradores_admitidos' => 'integer|max_length[11]',
		'total_colaboradores_demitidos' => 'integer|max_length[11]',
		'total_colaboradores_justa_causa' => 'integer|max_length[11]',
		'total_colaboradores_desligados' => 'integer|max_length[11]',
		'total_demissoes_desligamentos' => 'integer|max_length[11]',
		'total_acidentes' => 'integer|max_length[11]',
		'total_maternidade' => 'integer|max_length[11]',
		'total_aposentadoria' => 'integer|max_length[11]',
		'total_doenca' => 'integer|max_length[11]',
		'total_faltas_st' => 'integer|max_length[11]',
		'total_faltas_cd' => 'integer|max_length[11]',
		'total_faltas_gp' => 'integer|max_length[11]',
		'total_faltas_cdh' => 'integer|max_length[11]',
		'total_faltas_icom' => 'integer|max_length[11]',
		'total_faltas_adm' => 'integer|max_length[11]',
		'total_faltas_prj' => 'integer|max_length[11]',
		'total_colaboradores' => 'integer|max_length[11]',
		'total_atrasos_4_horas' => 'integer|max_length[11]',
		'total_atrasos_8_horas' => 'integer|max_length[11]',
		'total_faltas_1_dia' => 'integer|max_length[11]',
		'total_faltas_2_dias' => 'integer|max_length[11]',
		'total_faltas_3_dias' => 'integer|max_length[11]'
	];

}
