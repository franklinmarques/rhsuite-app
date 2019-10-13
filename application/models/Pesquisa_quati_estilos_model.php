<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_quati_estilos_model extends MY_Model
{
	protected static $table = 'pesquisa_quati_estilos';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'nome' => 'required|max_length[255]',
		'laudo_comportamental_padrao' => 'max_length[4294967295]',
		'perfil_preponderante' => 'required|exact_length[1]',
		'atitude_primaria' => 'required|exact_length[1]',
		'atitude_secundaria' => 'required|exact_length[1]'
	];

}
