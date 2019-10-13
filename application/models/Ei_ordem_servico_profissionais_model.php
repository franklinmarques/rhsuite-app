<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_ordem_servico_profissionais_model extends MY_Model
{
	protected static $table = 'ei_ordem_servico_profissionais';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_ordem_servico_escola' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_supervisor' => 'is_natural_no_zero|max_length[11]',
		'id_usuario_sub1' => 'integer|max_length[11]',
		'data_substituicao1' => 'valid_date',
		'id_usuario_sub2' => 'integer|max_length[11]',
		'data_substituicao2' => 'valid_date',
		'id_departamento' => 'integer|max_length[11]',
		'id_area' => 'integer|max_length[11]',
		'id_setor' => 'integer|max_length[11]',
		'id_cargo' => 'integer|max_length[11]',
		'id_funcao' => 'integer|max_length[11]',
		'municipio' => 'max_length[255]',
		'valor_hora' => 'decimal|max_length[11]',
		'qtde_dias' => 'decimal|max_length[5]',
		'qtde_semanas' => 'integer|max_length[1]',
		'horas_diarias' => 'decimal|max_length[6]',
		'horas_semanais' => 'decimal|max_length[6]',
		'horas_mensais' => 'decimal|max_length[7]',
		'horas_semestre' => 'decimal|max_length[7]',
		'desconto_mensal_1' => 'required|decimal|max_length[7]',
		'desconto_mensal_2' => 'required|decimal|max_length[7]',
		'desconto_mensal_3' => 'required|decimal|max_length[7]',
		'desconto_mensal_4' => 'required|decimal|max_length[7]',
		'desconto_mensal_5' => 'required|decimal|max_length[7]',
		'valor_hora_operacional' => 'decimal|max_length[11]',
		'valor_mensal_1' => 'decimal|max_length[11]',
		'valor_mensal_2' => 'decimal|max_length[11]',
		'valor_mensal_3' => 'decimal|max_length[11]',
		'valor_mensal_4' => 'decimal|max_length[11]',
		'valor_mensal_5' => 'decimal|max_length[11]',
		'valor_mensal_6' => 'decimal|max_length[11]',
		'desconto_mensal_6' => 'required|decimal|max_length[7]',
		'valor_hora_mensal' => 'decimal|max_length[11]',
		'desconto_mensal_sub1_1' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub1_2' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub1_3' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub1_4' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub1_5' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub1_6' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub2_1' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub2_2' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub2_3' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub2_4' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub2_5' => 'required|decimal|max_length[11]',
		'desconto_mensal_sub2_6' => 'required|decimal|max_length[11]',
		'horas_mensais_custo' => 'valid_time',
		'data_inicio_contrato' => 'valid_date',
		'data_termino_contrato' => 'valid_date',
		'pagamento_inicio' => 'decimal|max_length[11]',
		'pagamento_reajuste' => 'decimal|max_length[11]'
	];

}
