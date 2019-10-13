<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensionamento_crono_analises_model extends MY_Model
{
	protected static $table = 'dimensionamento_crono_analises';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'id_processo' => 'is_natural_no_zero|max_length[11]',
		'data_inicio' => 'required|valid_date',
		'data_termino' => 'required|valid_date',
		'status' => 'exact_length[1]',
		'base_tempo' => 'exact_length[1]',
		'unidade_producao' => 'max_length[30]'
	];

}
