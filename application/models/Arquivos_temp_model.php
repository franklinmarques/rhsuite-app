<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Arquivos_temp_model extends MY_Model
{
	protected static $table = 'arquivos_temp';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'arquivo' => 'uploaded[arquivo]|max_length[65535]',
		'usuario' => 'integer|max_length[11]'
	];

	protected $uploadConfig = [
		'arquivo' => ['upload_path' => './arquivos/temp/', 'allowed_types' => '*']
	];

	protected $beforeInsert = ['configurarUsuario'];

	//==========================================================================
	protected function configurarUsuario($data)
	{
		if (array_key_exists('data', $data) === false) {
			return $data;
		}

		$data['data']['usuario'] = $this->session->userdata('id');

		return $data;
	}

}
