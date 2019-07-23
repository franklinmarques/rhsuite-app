<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_contratos_model extends MY_Model
{
    protected static $table = 'icom_contratos';

    protected static $primaryKey = 'codigo';

    protected $validationRules = [
        'codigo' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'codigo_proposta' => 'required|is_natural_no_zero|max_length[11]',
        'id_cliente' => 'required|is_natural_no_zero|max_length[11]',
        'centro_custo' => 'max_length[255]',
        'data_vencimento' => 'required|valid_date',
        'status_ativo' => 'required|in_list[0,1]',
        'arquivo' => 'uploaded[arquivo]|mime_in[pdf]|max_length[255]'
    ];

    protected $uploadConfig = ['arquivo' => ['upload_path' => './arquivos/icom/contratos/', 'allowed_types' => 'pdf']];

    protected static $status = ['1' => 'Ativo', '0' => 'Inativo'];

}
