<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_clientes_acessos_model extends MY_Model
{
	protected static $table = 'cursos_clientes_acessos';

	protected static $createdField = 'data_acesso';

	protected static $updatedField = 'data_atualizacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_curso_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_pagina' => 'required|is_natural_no_zero|max_length[11]',
		'data_acesso' => 'required|valid_datetime',
		'data_atualizacao' => 'valid_datetime',
		'tempo_estudo' => 'valid_time',
		'data_finalizacao' => 'valid_datetime',
		'status' => 'required|integer|max_length[11]'
	];

}
