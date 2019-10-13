<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_faturamento_consolidado_model extends MY_Model
{
	protected static $table = 'ei_faturamento_consolidado';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'cargo' => 'required|max_length[255]',
		'funcao' => 'required|max_length[255]',
		'valor_hora_mes1' => 'required|decimal|max_length[11]',
		'valor_hora_mes2' => 'required|decimal|max_length[11]',
		'valor_hora_mes3' => 'required|decimal|max_length[11]',
		'valor_hora_mes4' => 'required|decimal|max_length[11]',
		'valor_hora_mes5' => 'required|decimal|max_length[11]',
		'valor_hora_mes6' => 'required|decimal|max_length[11]',
		'valor_hora_mes7' => 'required|decimal|max_length[11]',
		'total_horas_mes1' => 'required|max_length[20]',
		'total_horas_mes2' => 'required|max_length[20]',
		'total_horas_mes3' => 'required|max_length[20]',
		'total_horas_mes4' => 'required|max_length[20]',
		'total_horas_mes5' => 'required|max_length[20]',
		'total_horas_mes6' => 'required|max_length[20]',
		'total_horas_mes7' => 'required|max_length[20]',
		'valor_faturado_mes1' => 'decimal|max_length[11]',
		'valor_faturado_mes2' => 'decimal|max_length[11]',
		'valor_faturado_mes3' => 'decimal|max_length[11]',
		'valor_faturado_mes4' => 'decimal|max_length[11]',
		'valor_faturado_mes5' => 'decimal|max_length[11]',
		'valor_faturado_mes6' => 'decimal|max_length[11]',
		'valor_faturado_mes7' => 'decimal|max_length[11]',
		'total_escolas' => 'integer|max_length[11]',
		'total_alunos' => 'integer|max_length[11]',
		'total_cuidadores' => 'integer|max_length[11]',
		'total_horas_projetadas' => 'max_length[12]',
		'total_horas_realizadas' => 'max_length[12]',
		'receita_projetada' => 'decimal|max_length[11]',
		'receita_efetuada' => 'decimal|max_length[11]',
		'pagamentos_efetuados' => 'decimal|max_length[11]',
		'resultado' => 'decimal|max_length[11]',
		'resultado_percentual' => 'decimal|max_length[6]'
	];

}
