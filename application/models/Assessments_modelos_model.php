<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Assessments_modelos_model extends MY_Model
{
	protected static $table = 'assessments_modelos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[50]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'tipo' => 'required|exact_length[1]',
		'observacoes' => 'max_length[4294967295]',
		'instrucoes' => 'max_length[4294967295]',
		'aleatorizacao' => 'is_natural|less_than_equal_to[1]'
	];

	protected static $tipo = [
		'C' => 'Pesquisa de Clima Organizacional',
		'P' => 'Pesquisa de Perfil Profissional (uma única resposta)',
		'M' => 'Pesquisa de Perfil Profissional (múltiplas respostas)',
		'E' => 'Avaliação de Personalidade (Eneagrama)',
		'Q' => 'Avaliação de Personalidade (Tipologia Junguiana)',
		'O' => 'Avaliação de Personalidade (Orientações de Vida)',
		'N' => 'Avaliação de Potencial (NineBox)'
	];

}
