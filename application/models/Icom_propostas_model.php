<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_propostas_model extends MY_Model
{
	protected static $table = 'icom_propostas';

	protected static $primaryKey = 'codigo';

	protected $validationRules = [
		'codigo' => 'required|is_natural_no_zero|max_length[11]',
		'id_cliente' => 'required|is_natural_no_zero|max_length[11]',
		'id_setor' => 'required|is_natural_no_zero|max_length[11]',
		'descricao' => 'required|max_length[255]',
		'tipo' => 'in_list[P,C]',
		'data_entrega' => 'required|valid_date',
		'probabilidade_fechamento' => 'is_natural|less_than_equal_to[100]',
		'valor' => 'required|numeric|max_length[11]',
		'status' => 'required|in_list[A,G,P]',
		'custo_produto_servico' => 'numeric|max_length[11]',
		'custo_administrativo' => 'numeric|max_length[11]',
		'impostos' => 'numeric|max_length[11]',
		'margem_liquida' => 'numeric|max_length[11]',
		'margem_liquida_percentual' => 'numeric|less_than_equal_to[100]',
		'detalhes' => 'max_length[65535]',
		'arquivo' => 'uploaded[arquivo]|mime_in[arquivo.pdf]|max_length[255]'
	];

	protected $uploadConfig = ['arquivo' => ['upload_path' => './arquivos/icom/propostas/', 'allowed_types' => 'pdf']];

	protected static $tipo = ['P' => 'Padrão', 'C' => 'Customizada'];

	protected static $status = ['A' => 'Aberta', 'G' => 'Ganha', 'P' => 'Perdida'];

}
