<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Abcbr304_processos_model extends MY_Model
{
	protected static $table = 'abcbr304_processos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|integer|max_length[11]',
		'id_menu' => 'integer|max_length[11]',
		'url_pagina' => 'required|max_length[255]',
		'orientacoes_gerais' => 'required|max_length[65535]',
		'processo_1' => 'max_length[255]',
		'processo_2' => 'max_length[255]',
		'documentacao_1' => 'max_length[255]',
		'documentacao_2' => 'max_length[255]'
	];

}
