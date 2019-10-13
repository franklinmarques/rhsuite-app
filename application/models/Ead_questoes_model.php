<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_questoes_model extends MY_Model
{
	protected static $table = 'cursos_questoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[150]',
		'id_pagina' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'exact_length[1]',
		'conteudo' => 'max_length[4294967295]',
		'feedback_correta' => 'max_length[65535]',
		'feedback_incorreta' => 'max_length[65535]',
		'observacoes' => 'max_length[65535]',
		'aleatorizacao' => 'exact_length[1]',
		'id_biblioteca' => 'is_natural_no_zero|max_length[11]',
		'id_copia' => 'is_natural_no_zero|max_length[11]'
	];

}
