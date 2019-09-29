<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_escolas_model extends MY_Model
{
    protected static $table = 'ei_escolas';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[100]',
        'id_diretoria' => 'required|is_natural_no_zero|max_length[11]',
        'codigo' => 'is_natural_no_zero|max_length[4]',
        'endereco' => 'max_length[255]',
        'numero' => 'is_natural_no_zero|max_length[11]',
        'complemento' => 'max_length[255]',
        'bairro' => 'max_length[50]',
        'codigo_municipal' => 'required|is_natural_no_zero|max_length[11]',
        'telefone' => 'max_length[30]',
        'telefone_contato' => 'max_length[30]',
        'email' => 'valid_email|max_length[255]',
        'cep' => 'valid_cep|max_length[20]',
        'pessoas_contato' => 'max_length[4294967295]',
        'periodo_manha' => 'integer|max_length[4]',
        'periodo_tarde' => 'integer|max_length[4]',
        'periodo_noite' => 'integer|max_length[4]'
    ];

}
