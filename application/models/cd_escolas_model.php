<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_escolas_model extends MY_Model
{
    protected static $table = 'cd_escolas';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[100]',
        'id_diretoria' => 'required|is_natural_no_zero|max_length[11]',
        'endereco' => 'max_length[255]',
        'numero' => 'is_natural_no_zero|max_length[11]',
        'complemento' => 'max_length[255]',
        'bairro' => 'max_length[50]',
        'municipio' => 'required|max_length[100]',
        'telefone' => 'max_length[30]',
        'telefone_contato' => 'max_length[30]',
        'email' => 'valid_email|max_length[255]',
        'cep' => 'valid_cep|max_length[20]',
        'periodo_manha' => 'is_natural|less_than_equal_to[1]',
        'periodo_tarde' => 'is_natural|less_than_equal_to[1]',
        'periodo_noite' => 'is_natural|less_than_equal_to[1]'
    ];

}
