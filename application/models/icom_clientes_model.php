<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_clientes_model extends MY_Model
{
    protected static $table = 'icom_clientes';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[255]',
        'contato_principal' => 'max_length[255]',
        'telefone_principal' => 'max_length[255]',
        'email_principal' => 'valid_email|max_length[255]',
        'contato_secundario' => 'max_length[255]',
        'telefone_secundario' => 'max_length[255]',
        'email_secundario' => 'valid_email|max_length[255]'
    ];

}
