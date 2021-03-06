<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_alocados_model extends MY_Model
{
	protected static $table = 'icom_alocados';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'is_natural_no_zero|max_length[11]',
		'nome_usuario' => 'required|max_length[255]',
		'id_funcao' => 'is_natural_no_zero|max_length[11]',
		'matricula' => 'integer|max_length[11]',
		'categoria' => 'required|in_list[CLT,MEI]',
		'valor_hora_mei' => 'decimal|max_length[11]',
		'qtde_horas_mei' => 'valid_time',
		'qtde_horas_dia_mei' => 'valid_time',
		'valor_mes_clt' => 'numeric|max_length[11]',
		'qtde_meses_clt' => 'valid_time',
		'qtde_horas_dia_clt' => 'valid_time',
		'horario_entrada' => 'valid_time',
		'horario_intervalo' => 'valid_time',
		'horario_retorno' => 'valid_time',
		'horario_saida' => 'valid_time',
		'desconto_folha' => 'max_length[10]',
		'comprometimento' => 'is_natural_no_zero|less_than_equal_to[5]',
		'pontualidade' => 'is_natural_no_zero|less_than_equal_to[5]',
		'script' => 'is_natural_no_zero|less_than_equal_to[5]',
		'simpatia' => 'is_natural_no_zero|less_than_equal_to[5]',
		'empatia' => 'is_natural_no_zero|less_than_equal_to[5]',
		'postura' => 'is_natural_no_zero|less_than_equal_to[5]',
		'ferramenta' => 'is_natural_no_zero|less_than_equal_to[5]',
		'tradutorio' => 'is_natural_no_zero|less_than_equal_to[5]',
		'linguistico' => 'is_natural_no_zero|less_than_equal_to[5]',
		'neutralidade' => 'is_natural_no_zero|less_than_equal_to[5]',
		'discricao' => 'is_natural_no_zero|less_than_equal_to[5]',
		'fidelidade' => 'is_natural_no_zero|less_than_equal_to[5]',
		'extra_1' => 'is_natural_no_zero|less_than_equal_to[5]',
		'extra_2' => 'is_natural_no_zero|less_than_equal_to[5]',
		'extra_3' => 'is_natural_no_zero|less_than_equal_to[5]'
	];

	protected $beforeDelete = ['restaurarSaldoBancoHoras'];

	protected $afterDelete = ['atualizarSaldoBancoHoras'];

	protected static $categoria = ['CLT' => 'CLT', 'MEI' => 'MEI'];

	protected static $nivelPerformance = [
		'1' => 'Muito abaixo do necessário',
		'2' => 'Um pouco abaixo do necessário',
		'3' => 'Dentro da média e do necessário',
		'4' => 'Um pouco acima do necessário',
		'5' => 'Muito acima do necessário'
	];

	//==========================================================================
	protected function restaurarSaldoBancoHoras($data)
	{
		$this->db->trans_start();

		if (!empty($data[self::$primaryKey]) == false) {
			return $data;
		}

		$row = $this->db
			->select('a.id_usuario, b.banco_horas_icom')
			->select("SUM(TIME_TO_SEC(c.saldo_banco_horas)) AS saldo_banco_horas", false)
			->join('usuarios b', 'b.id = a.id_usuario')
			->join('icom_apontamento c', 'c.id_alocado = a.id', 'left')
			->where_in('a.id', $data[self::$primaryKey])
			->where('c.saldo_banco_horas IS NOT NULL')
			->get('icom_alocados a')
			->row();

		if (empty($row)) {
			return $data;
		}

		$this->load->helper('time');

		$bancoHoras = timeToSec($row->banco_horas_icom) - ($row->saldo_banco_horas ?? 0);
		$this->db->update('usuarios', ['banco_horas_icom' => secToTime($bancoHoras)], ['id' => $row->id_usuario]);
	}

	//==========================================================================
	protected function atualizarSaldoBancoHoras($data)
	{
		$this->db->trans_complete();
	}

}
