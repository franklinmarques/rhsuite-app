<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Biblioteca_questoes_model extends MY_Model
{
	protected static $table = 'biblioteca_questoes';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[150]',
		'tipo' => 'required|exact_length[1]',
		'conteudo' => 'required|max_length[4294967295]',
		'feedback_correta' => 'max_length[65535]',
		'feedback_incorreta' => 'max_length[65535]',
		'observacoes' => 'max_length[65535]',
		'aleatorizacao' => 'exact_length[1]',
		'id_copia' => 'is_natural_no_zero|max_length[11]'
	];

}
