<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_alocacao_model extends MY_Model
{
	protected static $table = 'icom_alocacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'id_depto' => 'required|is_natural_no_zero|max_length[11]',
		'id_area' => 'required|is_natural_no_zero|max_length[11]',
		'id_setor' => 'required|is_natural_no_zero|max_length[11]',
		'mes' => 'required|is_natural_no_zero|less_than_equal_to[12]',
		'ano' => 'required|is_natural_no_zero|max_length[4]'
	];

	protected $beforeDelete = ['restaurarSaldoBancoHoras'];

	protected $afterDelete = ['atualizarSaldoBancoHoras'];

	//==========================================================================
	protected function restaurarSaldoBancoHoras($data)
	{
		$this->db->trans_start();

		if (!empty($data[self::$primaryKey]) == false) {
			return $data;
		}

		$rows = $this->db
			->select('b.id_usuario, c.banco_horas_icom')
			->select("SUM(TIME_TO_SEC(d.saldo_banco_horas)) AS saldo_banco_horas", false)
			->join('icom_alocados b', 'b.id_alocacao = a.id')
			->join('usuarios c', 'c.id = b.id_usuario')
			->join('icom_apontamento d', 'd.id_alocado = b.id', 'left')
			->where_in('a.id', $data[self::$primaryKey])
			->where('d.saldo_banco_horas IS NOT NULL')
			->group_by('c.id')
			->get('icom_alocacao a')
			->result();

		if (empty($rows)) {
			return $data;
		}

		$this->load->helper('time');

		foreach ($rows as $row) {
			$bancoHoras = timeToSec($row->banco_horas_icom) - ($row->saldo_banco_horas ?? 0);
			$this->db->update('usuarios', ['banco_horas_icom' => secToTime($bancoHoras)], ['id' => $row->id_usuario]);
		}
	}

	//==========================================================================
	protected function atualizarSaldoBancoHoras($data)
	{
		$this->db->trans_complete();
	}

}
