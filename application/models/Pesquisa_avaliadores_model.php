<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_avaliadores_model extends MY_Model
{
	protected static $table = 'pesquisa_avaliadores';

	protected static $createdField = 'data_acesso';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_pesquisa' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliador' => 'required|is_natural_no_zero|max_length[11]',
		'id_avaliado' => 'is_natural_no_zero|max_length[11]',
		'data_acesso' => 'valid_datetime',
		'data_finalizacao' => 'valid_datetime',
		'estilo_personalidade_majoritario' => 'max_length[4294967295]',
		'estilo_personalidade_secundario' => 'max_length[4294967295]',
		'laudo_comportamental_padrao' => 'max_length[4294967295]'
	];

}
