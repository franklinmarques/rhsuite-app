<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_demissao_model extends MY_Model
{
	protected static $table = 'usuarios_demissao';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'data_demissao' => 'required|valid_date',
		'motivo_demissao' => 'required|integer|max_length[1]',
		'observacoes' => 'max_length[4294967295]'
	];

	protected static $motivoDemissao = [
		'1' => 'Demissão sem justa causa',
		'2' => 'Demissão por justa causa',
		'3' => 'Pedido de demissão',
		'4' => 'Término do contrato',
		'5' => 'Rescisão antecipada pelo empregado',
		'6' => 'Rescisão antecipada pelo empregador',
		'7' => 'Desistência da vaga',
		'8' => 'Rescisão estagiário',
		'9' => 'Rescisão por acordo'
	];

}
