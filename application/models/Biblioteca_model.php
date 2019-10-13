<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Biblioteca_model extends MY_Model
{
	protected static $table = 'biblioteca';

	protected static $createdField = 'datacadastro';

	protected static $updatedField = 'dataeditado';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'usuario' => 'required|integer|max_length[11]',
		'tipo' => 'required|integer|max_length[11]',
		'categoria' => 'required|integer|max_length[11]',
		'titulo' => 'required|max_length[255]',
		'descricao' => 'required|max_length[4294967295]',
		'link' => 'required|max_length[255]',
		'disciplina' => 'required|max_length[255]',
		'anoserie' => 'required|max_length[255]',
		'temacurricular' => 'required|max_length[255]',
		'uso' => 'required|max_length[255]',
		'licenca' => 'required|max_length[255]',
		'produzidopor' => 'required|max_length[255]',
		'tags' => 'required|max_length[4294967295]',
		'datacadastro' => 'required|valid_datetime',
		'dataeditado' => 'required|valid_datetime'
	];

}
