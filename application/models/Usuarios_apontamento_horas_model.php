<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_apontamento_horas_model extends MY_Model
{
	protected static $table = 'usuarios_apontamento_horas';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'data_hora' => 'required|valid_date',
		'turno_evento' => 'required|in_list[E,S]',
		'modo_cadastramento' => 'required|in_list[A,M]',
		'id_depto' => 'is_natural_no_zero|max_length[11]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'id_setor' => 'is_natural_no_zero|max_length[11]',
		'justificativa' => 'max_length[65535]',
		'aceite_justificativa' => 'is_natural|less_than_equal_to[1]',
		'data_aceite' => 'valid_date',
		'observacoes_aceite' => 'max_length[65535]',
		'id_usuario_aceite' => 'is_natural_no_zero|max_length[11]'
	];

	protected $beforeUpdate = ['registrarAceite'];

	protected static $turnoEvento = [
		'E' => 'Entrada',
		'S' => 'Saída'
	];

	protected static $modoCadastramento = [
		'A' => 'Automático',
		'M' => 'Manual'
	];

	protected static $aceiteJustificativa = [
		'1' => 'Aceita',
		'0' => 'Não aceita'
	];

	//==========================================================================
	protected function registrarAceite($data)
	{
		if (empty($data['data'])) {
			return $data;
		}

		$data['data']['data_aceite'] = date('Y-m-d H:i:s');
		$data['data']['id_usuario_aceite'] = $this->session->userdata('id');

		return $data;
	}

}
