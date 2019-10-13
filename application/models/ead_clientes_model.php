<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_clientes_model extends MY_Model
{
	protected static $table = 'cursos_clientes';

	protected static $createdField = 'data_cadastro';

	protected static $updatedField = 'data_edicao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'cliente' => 'required|max_length[255]',
		'email' => 'valid_email|required|max_length[255]',
		'senha' => 'required|max_length[32]',
		'token' => 'required|max_length[255]',
		'foto' => 'uploaded[foto]|mime_in[foto.gif,jpg,png]|max_length[255]',
		'data_cadastro' => 'required|valid_datetime',
		'data_edicao' => 'valid_datetime|after_datetime[data_cadastro]',
		'status' => 'required|integer|max_length[2]'
	];

	protected $uploadConfig = ['foto' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png']];

	protected $beforeInsert = ['encriptarSenha', 'gerarToken'];

	protected $beforeUpdate = ['encriptarSenha'];

	protected static $status = [
		'1' => 'Ativo',
		'0' => 'Inativo'
	];

	//==========================================================================
	protected function encriptarSenha($data)
	{
		if (array_key_exists('senha', $data['data'] ?? []) === false) {
			return $data;
		}

		if (strlen($data['data']['senha']) > 0) {
			if ($this->load->is_loaded('Auth') == false) {
				$this->load->library('Auth');
			}

			$data['data']['senha'] = $this->auth->encryptPassword($data['data']['senha']);
		} else {
			unset($data['data']['senha']);
		}

		return $data;
	}

	//==========================================================================
	protected function gerarToken($data)
	{
		if (array_key_exists('data', $data) == false) {
			return $data;
		}

		$data['data']['token'] = uniqid();

		return $data;
	}

}
