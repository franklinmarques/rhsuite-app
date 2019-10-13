<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_mapa_visitacao_model extends MY_Model
{
	protected static $table = 'ei_mapa_visitacao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_mapa_unidade' => 'required|is_natural_no_zero|max_length[11]',
		'data_visita' => 'required|valid_date',
		'data_visita_anterior' => 'valid_date',
		'id_supervisor_visitante' => 'is_natural_no_zero|max_length[11]',
		'supervisor_visitante' => 'required|max_length[255]',
		'cliente' => 'required|integer|max_length[11]',
		'municipio' => 'required|max_length[255]',
		'escola' => 'required|max_length[255]',
		'unidade_visitada' => 'required|integer|max_length[11]',
		'prestadores_servicos_tratados' => 'max_length[255]',
		'coordenador_responsavel' => 'integer|max_length[11]',
		'motivo_visita' => 'integer|max_length[1]',
		'gastos_materiais' => 'required|decimal|max_length[11]',
		'sumario_visita' => 'max_length[4294967295]',
		'observacoes' => 'max_length[4294967295]'
	];

}
