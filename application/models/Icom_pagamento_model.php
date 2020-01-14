<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_pagamento_model extends MY_Model
{
	protected static $table = 'icom_pagamento';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_profissional_alocado' => 'required|is_natural_no_zero|max_length[11]',
		'nota_fiscal' => 'required|max_length[255]',
		'mes_referencia' => 'required|is_natural_no_zero|less_than_equal_to[12]',
		'ano_referencia' => 'required|is_natural_no_zero|max_length[4]',
		'cnpj' => 'valid_cnpj',
		'tipo_pagamento' => 'in_list[D,I]',
		'total_sessoes' => 'required|integer|max_length[11]',
		'valor_total' => 'required|numeric|max_length[11]',
		'data_emissao' => 'valid_datetime',
		'assinatura' => 'max_length[255]'
	];

	protected $beforeInsert = ['setDataEmissao'];


	//==========================================================================


	protected function setDataEmissao($data)
	{
		if (array_key_exists('data', $data) === false) {
			return $data;
		}

		$data['data']['data_emissao'] = date('Y-m-d H:i:s');

		return $data;
	}

}
