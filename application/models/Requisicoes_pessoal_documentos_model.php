<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_documentos_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal_documentos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_candidato' => 'required|is_natural_no_zero|max_length[11]',
		'nome_arquivo' => 'required|max_length[255]',
		'tipo_arquivo' => 'required|max_length[255]',
		'data_upload' => 'required|valid_datetime'
	];

}
