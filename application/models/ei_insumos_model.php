<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_insumos_model extends MY_Model
{
	protected static $table = 'ei_insumos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'tipo' => 'required|max_length[255]'
	];

	protected $beforeFind = ['filtrarPorEmpresa'];

	//==========================================================================

	protected function filtrarPorEmpresa($id)
	{
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
	}

}
