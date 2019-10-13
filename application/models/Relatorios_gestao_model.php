<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios_gestao_model extends MY_Model
{
	protected static $table = 'relatorios_gestao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'is_natural_no_zero|max_length[11]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'id_setor' => 'is_natural_no_zero|max_length[11]',
		'mes_referencia' => 'required|numeric|max_length[2]',
		'ano_referencia' => 'required|is_natural_no_zero|max_length[4]',
		'data_fechamento' => 'required|valid_date',
		'indicadores' => 'max_length[65535]',
		'riscos_oportunidades' => 'max_length[65535]',
		'ocorrencias' => 'max_length[65535]',
		'necessidades_investimentos' => 'max_length[65535]',
		'objetivos_imediatos' => 'max_length[65535]',
		'objetivos_futuros' => 'max_length[65535]',
		'parecer_final' => 'max_length[65535]',
		'observacoes' => 'max_length[65535]',
		'status' => 'required|exact_length[1]'
	];

}
