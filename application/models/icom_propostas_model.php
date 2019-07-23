<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_propostas_model extends MY_Model
{
    protected static $table = 'icom_propostas';

    protected static $primaryKey = 'codigo';

    protected $validationRules = [
        'codigo' => 'required|is_natural_no_zero|max_length[11]',
        'id_cliente' => 'required|is_natural_no_zero|max_length[11]',
        'descricao' => 'required|max_length[255]',
        'data_entrega' => 'required|valid_date',
        'valor' => 'required|numeric|max_length[10]',
        'status' => 'required|in_list[A,G,P]',
        'custo_produto_servico' => 'numeric|max_length[10]',
        'custo_administrativo' => 'numeric|max_length[10]',
        'impostos' => 'numeric|max_length[10]',
        'margem_liquida' => 'numeric|max_length[10]',
        'arquivo' => 'uploaded[arquivo]|mime_in[pdf]|max_length[255]'
    ];

    protected $uploadConfig = ['arquivo' => ['upload_path' => './arquivos/icom/propostas/', 'allowed_types' => 'pdf']];

    protected static $status = ['A' => 'Aberta', 'G' => 'Ganha', 'P' => 'Perdida'];

}
