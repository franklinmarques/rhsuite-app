<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_clientes_treinamentos_model extends MY_Model
{
	protected static $table = 'cursos_clientes_treinamentos';

	protected static $createdField = 'data_cadastro';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_curso' => 'is_natural_no_zero|max_length[11]',
		'data_cadastro' => 'required|valid_datetime',
		'data_inicio' => 'valid_date|after_or_equal_date[data_cadastro]',
		'data_maxima' => 'valid_date|after_or_equal_date[data_inicio]',
		'colaboradores_maximo' => 'integer|max_length[11]',
		'nota_aprovacao' => 'integer|max_length[3]',
		'tipo_treinamento' => 'exact_length[1]',
		'local_treinamento' => 'exact_length[1]',
		'nome' => 'max_length[255]',
		'carga_horaria_presencial' => 'valid_time',
		'avaliacao_presencial' => 'integer|max_length[3]',
		'nome_fornecedor' => 'max_length[255]'
	];

}
