<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_usuarios_model extends MY_Model
{
	protected static $table = 'acessosistema';

	protected static $createdField = 'data_acesso';

	protected static $updatedField = 'data_atualizacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'usuario' => 'required|integer|max_length[11]',
		'tipo' => 'max_length[20]',
		'data_acesso' => 'required|valid_datetime',
		'data_atualizacao' => 'valid_datetime|after_datetime[data_acesso]',
		'data_saida' => 'valid_datetime|after_datetime[data_atualizacao]',
		'endereco_ip' => 'valid_ip|max_length[45]',
		'agente_usuario' => 'max_length[255]',
		'id_sessao' => 'max_length[128]'
	];

	protected $beforeInsert = ['configurarUsuario'];

	//==========================================================================
	protected function configurarUsuario($data)
	{
		if (array_key_exists('data', $data) === false) {
			return $data;
		}

		$data['data']['usuario'] = $this->session->userdata('id');
		$data['data']['tipo'] = $this->session->userdata('tipo');
		$data['data']['endereco_ip'] = $this->input->ip_address();
		$data['data']['agente_usuario'] = $this->input->user_agent();
		$data['data']['id_sessao'] = session_id();

		return $data;
	}

	//==========================================================================
	public function finalizar($data)
	{
		$this->disableUseTimestamps();

		$data['data_saida'] = date('Y-m-d H:i:s');

		$retorno = $this->update($data);

		$this->disableUseTimestamps(false);

		return $retorno;
	}

}
