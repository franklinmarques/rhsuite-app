<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_paginas_model extends MY_Model
{
	protected static $table = 'cursos_paginas';

	protected static $createdField = 'data_acesso';

	protected static $updatedField = 'data_editado';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_curso' => 'required|is_natural_no_zero|max_length[11]',
		'ordem' => 'required|integer|max_length[11]',
		'modulo' => 'required|max_length[20]',
		'titulo' => 'required|max_length[255]',
		'conteudo' => 'max_length[4294967295]',
		'pdf' => 'max_length[255]',
		'url' => 'max_length[255]',
		'arquivo_video' => 'max_length[255]',
		'categoriabiblioteca' => 'integer|max_length[11]',
		'titulobiblioteca' => 'max_length[255]',
		'tagsbiblioteca' => 'max_length[255]',
		'biblioteca' => 'integer|max_length[11]',
		'audio' => 'max_length[255]',
		'video' => 'max_length[255]',
		'autoplay' => 'required|integer|max_length[1]',
		'nota_corte' => 'integer|max_length[3]',
		'id_pagina_aprovacao' => 'is_natural_no_zero|max_length[11]',
		'id_pagina_reprovacao' => 'is_natural_no_zero|max_length[11]',
		'aleatorizacao' => 'exact_length[1]',
		'data_cadastro' => 'required|valid_datetime',
		'data_editado' => 'valid_datetime',
		'id_copia' => 'is_natural_no_zero|max_length[11]'
	];

}
