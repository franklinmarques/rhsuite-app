<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_lifo_estilos_model extends MY_Model
{
	protected static $table = 'pesquisa_lifo_estilos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[20]',
		'indice_resposta' => 'required|integer|max_length[1]',
		'estilo_personalidade_majoritario' => 'max_length[4294967295]',
		'estilo_personalidade_secundario' => 'max_length[4294967295]'
	];

}
